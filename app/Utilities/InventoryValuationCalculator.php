<?php

namespace App\Utilities;

use App\Models\InventoryValuationBalance;
use App\Models\InventoryValuationHistory;
use App\Models\Merchandise;
use App\Models\Product;
use App\Notifications\ProductPriceUpdated;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\Notification;

class InventoryValuationCalculator
{
    public static function calculate($model, $detail, $operation = 'subtract')
    {
        if (!isFeatureEnabledForCompany($model->company_id, 'Inventory Valuation')) {
            return;
        }

        $product = Product::find($detail['product_id']);

        $quantity = $operation == 'subtract' ? $detail['quantity'] * -1 : $detail['quantity'];

        $productHasQuantity = ($product->merchandises()->sum('available') - $quantity) > 0;

        if (!$product->hasCost() && $productHasQuantity) {
            return;
        }

        static::calcuateForLifoOrFifo($detail, $operation);

        if ($operation == 'add') {
            static::calcuateForAverage($detail);
        }
    }

    private static function calcuateForLifoOrFifo($detail, $operation)
    {
        foreach (['fifo', 'lifo'] as $method) {
            $product = Product::where('id', $detail['product_id'])->first();

            if ($operation == 'add' && (!isset($detail['unit_cost']) || $detail['unit_cost'] == 0)) {
                $detail['unit_cost'] = $product->{$method . '_unit_cost'};
            }

            if ($operation == 'add') {
                InventoryValuationBalance::firstOrCreate([
                    'product_id' => $detail['product_id'],
                    'type' => $method,
                    'model_detail_type' => $detail['model_type'] ?? get_class($detail),
                    'model_detail_id' => $detail['model_id'] ?? $detail->id,
                    'operation' => $operation,
                ], [
                    'quantity' => $detail['quantity'],
                    'original_quantity' => $detail['quantity'],
                    'unit_cost' => $detail['unit_cost'],
                ]);
            }

            static::calcuateNewUnitCost($detail, $operation, $method);
        }
    }

    private static function calcuateForAverage($detail)
    {
        $product = Product::where('id', $detail['product_id'])->first();

        if (!isset($detail['unit_cost']) || $detail['unit_cost'] == 0) {
            $detail['unit_cost'] = $product->average_unit_cost;
        }

        $totalQuantity = Merchandise::where('product_id', $detail['product_id'])->sum('available');

        $currentTotalCost = $product->average_unit_cost * ($totalQuantity - $detail['quantity']);

        $newTotalCost = $currentTotalCost + ($detail['quantity'] * $detail['unit_cost']);

        $newAverageUnitCost = $totalQuantity ? $newTotalCost / $totalQuantity : 0;

        if ($product->unitCost == $newAverageUnitCost) {
            return;
        }

        $product->update(['average_unit_cost' => $newAverageUnitCost]);

        InventoryValuationHistory::firstOrCreate([
            'product_id' => $product->id,
            'type' => 'average',
            'model_detail_type' => $detail['model_type'] ?? get_class($detail),
            'model_detail_id' => $detail['model_id'] ?? $detail->id,
            'operation' => 'add',
        ], [
            'unit_cost' => $newAverageUnitCost,
        ]);

        if ($product->inventory_valuation_method == 'average') {
            static::calculateNewPrice($product);
        }
    }

    public static function calcuateNewUnitCost($detail, $operation, $requestMethods = ['fifo', 'lifo'])
    {
        $methods = is_array($requestMethods) ? $requestMethods : [$requestMethods];

        foreach ($methods as $method) {
            $product = Product::where('id', $detail['product_id'])->first();

            $totalCost = InventoryValuationBalance::where('product_id', $detail['product_id'])->where('type', $method)->selectRaw('SUM(quantity*unit_cost) as total_cost')->first()->total_cost;

            $totalQuantity = InventoryValuationBalance::where('product_id', $detail['product_id'])->where('type', $method)->sum('quantity');

            $newUnitCost = $totalQuantity > 0 ? $totalCost / $totalQuantity : 0;

            if ($operation == 'subtract' && $newUnitCost == 0) {
                continue;
            }

            if ($product->unitCost == $newUnitCost) {
                continue;
            }

            $product->update([$method . '_unit_cost' => $newUnitCost]);

            InventoryValuationHistory::firstOrCreate([
                'product_id' => $product->id,
                'type' => $method,
                'model_detail_type' => $detail['model_type'] ?? get_class($detail),
                'model_detail_id' => $detail['model_id'] ?? $detail->id,
                'operation' => $operation,
            ], [
                'unit_cost' => $newUnitCost,
            ]);

            if ($product->inventory_valuation_method == $method) {
                static::calculateNewPrice($product);
            }
        }
    }

    private static function calculateNewPrice($product)
    {
        if ($product->profit_margin_amount == 0) {
            return;
        }

        if ($product->profit_margin_type == 'percent') {
            $percent = 1 + ($product->profit_margin_amount / 100);
            $newPrice = $product->unitCost * $percent;
        }

        if ($product->profit_margin_type == 'amount') {
            $newPrice = $product->unitCost + $product->profit_margin_amount;
        }

        if (!$product->company->isPriceBeforeTax()) {
            $newPrice = $newPrice * (1 + $product->tax->amount);
        }

        $price = $product->prices()->create([
            'fixed_price' => $newPrice,
            'is_active' => 0,
        ]);

        Notification::send(
            Notifiables::byPermission('Update Price', excludeAuthUser: false),
            new ProductPriceUpdated($price)
        );
    }
}
