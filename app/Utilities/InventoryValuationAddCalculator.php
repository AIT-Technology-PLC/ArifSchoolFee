<?php

namespace App\Utilities;

use App\Models\InventoryValuationBalance;
use App\Models\InventoryValuationHistory;
use App\Models\Merchandise;
use App\Models\Product;

class InventoryValuationAddCalculator
{
    public static function calculate($detail)
    {
        static::calcuateForLifo($detail);
        static::calcuateForFifo($detail);
        static::calcuateForAverage($detail);
    }

    private static function calcuateForLifo($detail)
    {
        InventoryValuationBalance::create([
            'type' => 'lifo',
            'product_id' => $detail['product_id'],
            'quantity' => $detail['quantity'],
            'unit_cost' => $detail['unit_cost'] ?? $detail->product->lifo_unit_cost,
        ]);

        $InventoryValuationBalances = InventoryValuationBalance::where('product_id', $detail['product_id'])->where('type', 'lifo')->get();
        $product = Product::where('id', $detail['product_id'])->first();
        $sumQuantityUnitCost = $InventoryValuationBalances->sum(function ($InventoryValuationBalance) {
            return $InventoryValuationBalance->quantity * $InventoryValuationBalance->unit_cost;
        });

        $sumQuantity = $InventoryValuationBalances->sum('quantity');
        $newUnitCost = $sumQuantity ? $sumQuantityUnitCost / $sumQuantity : 0;

        $product->update(['lifo_unit_cost' => $newUnitCost]);
        InventoryValuationHistory::create([
            'type' => 'lifo',
            'product_id' => $product->id,
            'unit_cost' => $newUnitCost,
        ]);
    }

    private static function calcuateForFifo($detail)
    {
        InventoryValuationBalance::create([
            'type' => 'fifo',
            'product_id' => $detail['product_id'],
            'quantity' => $detail['quantity'],
            'unit_cost' => $detail['unit_cost'] ?? $detail->product->fifo_unit_cost,
        ]);

        $InventoryValuationBalances = InventoryValuationBalance::where('product_id', $detail['product_id'])->where('type', 'fifo')->get();
        $product = Product::where('id', $detail['product_id'])->first();
        $sumQuantityUnitCost = $InventoryValuationBalances->sum(function ($InventoryValuationBalance) {
            return $InventoryValuationBalance->quantity * $InventoryValuationBalance->unit_cost;
        });

        $sumQuantity = $InventoryValuationBalances->sum('quantity');
        $newUnitCost = $sumQuantity ? $sumQuantityUnitCost / $sumQuantity : 0;

        $product->update(['fifo_unit_cost' => $newUnitCost]);
        InventoryValuationHistory::create([
            'type' => 'fifo',
            'product_id' => $product->id,
            'unit_cost' => $newUnitCost,
        ]);
    }

    private static function calcuateForAverage($detail)
    {
        $merchandises = Merchandise::where('product_id', $detail['product_id'])->get();
        $product = Product::where('id', $detail['product_id'])->first();
        $currentQuantity = $merchandises->sum('available');
        $currentTotalCost = $product->average_unit_cost * ($currentQuantity - $detail['quantity']);
        $totalCost = $currentTotalCost + ($detail['quantity'] * $detail['unit_cost']);
        $new_average_unit_cost = $currentQuantity ? $totalCost / $currentQuantity : 0;

        $product->update(['average_unit_cost' => $new_average_unit_cost]);
        InventoryValuationHistory::create([
            'type' => 'average',
            'product_id' => $product->id,
            'unit_cost' => $new_average_unit_cost,
        ]);
    }
}
