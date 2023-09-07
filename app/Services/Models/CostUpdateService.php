<?php

namespace App\Services\Models;

use App\Actions\ApproveTransactionAction;
use App\Actions\RejectTransactionAction;
use App\Models\InventoryValuationBalance;
use App\Models\InventoryValuationHistory;
use App\Models\Merchandise;
use App\Notifications\CostUpdateApproved;
use App\Notifications\CostUpdateRejected;
use Illuminate\Support\Facades\DB;

class CostUpdateService
{
    public function approve($costUpdate)
    {
        return DB::transaction(function () use ($costUpdate) {
            [$isExecuted, $message] = (new ApproveTransactionAction)->execute($costUpdate, CostUpdateApproved::class, 'Read Cost Update');

            if (!$isExecuted) {
                DB::rollBack();
                return [$isExecuted, $message];
            }

            foreach ($costUpdate->costUpdateDetails as $detail) {
                if ($detail->product->inventoryValuationHistories()->exists()) {
                    DB::rollBack();
                    return [false, $detail->product->name . ' has cost histories which can not be overridden.'];
                }

                $quantity = Merchandise::where('product_id', $detail->product_id)->sum('available');

                if ($quantity == 0) {
                    DB::rollBack();
                    return [false, $detail->product->name . ' is not available in inventory thus can not have cost.'];
                }

                $detail->product->update(['average_unit_cost' => $detail->average_unit_cost]);

                InventoryValuationHistory::create([
                    'type' => 'average',
                    'product_id' => $detail->product_id,
                    'unit_cost' => $detail->average_unit_cost,
                ]);

                foreach (['fifo', 'lifo'] as $method) {
                    if (is_null($detail->{$method . '_unit_cost'})) {
                        $detail->{$method . '_unit_cost'} = $detail->average_unit_cost;
                    }

                    InventoryValuationBalance::create([
                        'type' => $method,
                        'product_id' => $detail->product_id,
                        'quantity' => $quantity,
                        'original_quantity' => $quantity,
                        'unit_cost' => $detail->{$method . '_unit_cost'},
                    ]);

                    $detail->product->update([$method . '_unit_cost' => $detail->{$method . '_unit_cost'}]);

                    InventoryValuationHistory::create([
                        'type' => $method,
                        'product_id' => $detail->product_id,
                        'unit_cost' => $detail->{$method . '_unit_cost'},
                    ]);
                }
            }

            return [true, $message];
        });
    }

    public function reject($costUpdate)
    {
        if ($costUpdate->isApproved()) {
            return [false, 'You can not reject a cost update that is approved.'];
        }

        if ($costUpdate->isRejected()) {
            return [false, 'This cost update is already rejected.'];
        }

        return DB::transaction(function () use ($costUpdate) {
            [$isExecuted, $message] = (new RejectTransactionAction)->execute($costUpdate, CostUpdateRejected::class, 'Read Cost Update');

            if (!$isExecuted) {
                DB::rollBack();
                return [$isExecuted, $message];
            }

            return [true, $message];
        });
    }
}
