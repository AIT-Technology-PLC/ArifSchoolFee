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

            $InventoryValuationBalances = InventoryValuationBalance::where('product_id', $detail['product_id'])->where('type', $method)->get();
            $product = Product::where('id', $detail['product_id'])->first();
            $sumQuantityUnitCost = $InventoryValuationBalances->sum(function ($InventoryValuationBalance) {
                return $InventoryValuationBalance->quantity * $InventoryValuationBalance->unit_cost;
            });

            $sumQuantity = $InventoryValuationBalances->sum('quantity');
            $newUnitCost = $sumQuantity ? $sumQuantityUnitCost / $sumQuantity : 0;

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
        $currentQuantity = Merchandise::where('product_id', $detail['product_id'])->sum('available');
        $currentTotalCost = $product->average_unit_cost * ($currentQuantity - $detail['quantity']);
        $totalCost = $currentTotalCost + ($detail['quantity'] * $detail['unit_cost']);
        $newAverageUnitCost = $currentQuantity ? $totalCost / $currentQuantity : 0;

        $product->update(['average_unit_cost' => $newAverageUnitCost]);
        InventoryValuationHistory::create([
            'type' => 'average',
            'product_id' => $product->id,
            'unit_cost' => $newAverageUnitCost,
        ]);
    }
}
