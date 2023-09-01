<?php

namespace App\Utilities;

use App\Models\InventoryValuationBalance;
use App\Models\InventoryValuationHistory;
use App\Models\Merchandise;
use App\Models\Product;

class InventoryValuationCalculator
{
    public static function calculate($detail, $operation = 'subtract')
    {
        static::calcuateForLifoOrFifo($detail, $operation);

        if ($operation == 'add') {
            static::calcuateForAverage($detail);
        }
    }

    private static function calcuateForLifoOrFifo($detail, $operation)
    {
        foreach (['fifo', 'lifo'] as $method) {
            if ($operation == 'add') {
                InventoryValuationBalance::create([
                    'type' => $method,
                    'product_id' => $detail['product_id'],
                    'quantity' => $detail['quantity'],
                    'unit_cost' => $detail['unit_cost'] ?? $detail->product[$method . '_unit_cost'],
                ]);
            }

            $product = Product::where('id', $detail['product_id'])->first();

            $totalCost = InventoryValuationBalance::where('product_id', $detail['product_id'])->where('type', $method)->sum('quantity*unit_cost');

            $totalQuantity = InventoryValuationBalance::where('product_id', $detail['product_id'])->where('type', $method)->sum('quantity');

            $newUnitCost = $totalQuantity ? $totalCost / $totalQuantity : 0;

            $product->update([$method . '_unit_cost' => $newUnitCost]);

            InventoryValuationHistory::create([
                'type' => $method,
                'product_id' => $product->id,
                'unit_cost' => $newUnitCost,
            ]);
        }
    }

    private static function calcuateForAverage($detail)
    {
        $product = Product::where('id', $detail['product_id'])->first();

        $totalQuantity = Merchandise::where('product_id', $detail['product_id'])->sum('available');

        $currentTotalCost = $product->average_unit_cost * ($totalQuantity - $detail['quantity']);

        $newTotalCost = $currentTotalCost + ($detail['quantity'] * $detail['unit_cost']);

        $newAverageUnitCost = $totalQuantity ? $newTotalCost / $totalQuantity : 0;

        $product->update(['average_unit_cost' => $newAverageUnitCost]);

        InventoryValuationHistory::create([
            'type' => 'average',
            'product_id' => $product->id,
            'unit_cost' => $newAverageUnitCost,
        ]);
    }
}
