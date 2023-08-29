<?php

namespace App\Utilities;

use App\Models\InventoryValuationBalance;
use App\Models\InventoryValuationHistory;
use App\Models\Product;

class InventoryValuationSubtractCalculator
{
    public static function calculate($detail)
    {
        static::calcuateForLifo($detail);
        static::calcuateForFifo($detail);
    }

    private static function calcuateForLifo($detail)
    {
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
}
