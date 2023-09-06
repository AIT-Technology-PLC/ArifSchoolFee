<?php

namespace App\Services\Models;

use App\Actions\ApproveTransactionAction;
use App\Models\InventoryValuationBalance;
use App\Models\InventoryValuationHistory;
use App\Models\Merchandise;
use App\Models\Product;
use App\Notifications\CostUpdateApproved;
use Illuminate\Support\Facades\DB;

class CostUpdateService
{
    public function approve($costUpdate)
    {
        return DB::transaction(function () use ($costUpdate) {
            [$isExecuted, $message] = (new ApproveTransactionAction)->execute($costUpdate, CostUpdateApproved::class, 'Read Cost Update');

            foreach ($costUpdate->costUpdateDetails as $detail) {
                $quantity = Merchandise::where('product_id', $detail->product_id)->sum('available');

                $product = Product::where('id', $detail->product_id)->first();

                InventoryValuationBalance::where('product_id', $detail->product_id)->where('type', 'average')->update(['quantity' => 0]);

                InventoryValuationBalance::create([
                    'type' => 'average',
                    'product_id' => $detail->product_id,
                    'quantity' => $quantity,
                    'original_quantity' => $quantity,
                    'unit_cost' => $detail->average_unit_cost,
                ]);

                $totalQuantityAverage = Merchandise::where('product_id', $detail->product_id)->sum('available');

                $currentTotalCostAverage = Product::where('id', $detail->product_id)->first()->average_unit_cost * $totalQuantityAverage;

                $newAverageUnitCost = $totalQuantityAverage ? $currentTotalCostAverage / $totalQuantityAverage : 0;

                $product->update(['average_unit_cost' => $newAverageUnitCost]);

                InventoryValuationHistory::create([
                    'type' => 'average',
                    'product_id' => $detail->product_id,
                    'unit_cost' => $newAverageUnitCost,
                ]);

                foreach (['fifo', 'lifo'] as $method) {
                    if ($detail->{$method . '_unit_cost'} == null) {
                        continue;
                    }

                    InventoryValuationBalance::where('product_id', $detail->product_id)->where('type', $method)->update(['quantity' => 0]);

                    InventoryValuationBalance::create([
                        'type' => $method,
                        'product_id' => $detail->product_id,
                        'quantity' => $quantity,
                        'original_quantity' => $quantity,
                        'unit_cost' => $detail->{$method . '_unit_cost'},
                    ]);

                    $totalCost = InventoryValuationBalance::where('product_id', $detail->product_id)->where('type', $method)->selectRaw('SUM(quantity*unit_cost) as total_cost')->first()->total_cost;

                    $totalQuantity = InventoryValuationBalance::where('product_id', $detail->product_id)->where('type', $method)->sum('quantity');

                    $newUnitCost = $totalQuantity != 0 ? $totalCost / $totalQuantity : 0;

                    $product->update([$method . '_unit_cost' => $newUnitCost]);

                    InventoryValuationHistory::create([
                        'type' => $method,
                        'product_id' => $detail->product_id,
                        'unit_cost' => $newUnitCost,
                    ]);
                }
            }

            if (!$isExecuted) {
                DB::rollBack();
                return [$isExecuted, $message];
            }

            return [true, $message];
        });
    }
}
