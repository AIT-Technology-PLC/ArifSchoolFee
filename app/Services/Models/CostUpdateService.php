<?php

namespace App\Services\Models;

use App\Actions\ApproveTransactionAction;
use App\Actions\RejectTransactionAction;
use App\Models\InventoryValuationBalance;
use App\Models\InventoryValuationHistory;
use App\Models\Merchandise;
use App\Models\Product;
use App\Notifications\CostUpdateApproved;
use App\Notifications\CostUpdateRejected;
use Illuminate\Support\Facades\DB;

class CostUpdateService
{
    public function approve($costUpdate)
    {
        return DB::transaction(function () use ($costUpdate) {
            [$isExecuted, $message] = (new ApproveTransactionAction)->execute($costUpdate, CostUpdateApproved::class, 'Read Cost Update');

            foreach ($costUpdate->costUpdateDetails as $detail) {
                $quantity = Merchandise::withoutGlobalScopes([BranchScope::class])->where('product_id', $detail->product_id)->sum('available');

                $product = Product::where('id', $detail->product_id)->first();

                InventoryValuationBalance::where('product_id', $detail->product_id)->where('type', 'average')->where('quantity', '>', 0)->update(['quantity' => 0]);

                InventoryValuationBalance::create([
                    'type' => 'average',
                    'product_id' => $detail->product_id,
                    'quantity' => $quantity,
                    'original_quantity' => $quantity,
                    'unit_cost' => $detail->average_unit_cost,
                ]);

                $product->update(['average_unit_cost' => $detail->average_unit_cost]);

                InventoryValuationHistory::create([
                    'type' => 'average',
                    'product_id' => $detail->product_id,
                    'unit_cost' => $detail->average_unit_cost,
                ]);

                foreach (['fifo', 'lifo'] as $method) {
                    if ($detail->{$method . '_unit_cost'} == null) {
                        continue;
                    }

                    InventoryValuationBalance::where('product_id', $detail->product_id)->where('type', $method)->where('quantity', '>', 0)->update(['quantity' => 0]);

                    InventoryValuationBalance::create([
                        'type' => $method,
                        'product_id' => $detail->product_id,
                        'quantity' => $quantity,
                        'original_quantity' => $quantity,
                        'unit_cost' => $detail->{$method . '_unit_cost'},
                    ]);

                    $product->update([$method . '_unit_cost' => $detail->{$method . '_unit_cost'}]);

                    InventoryValuationHistory::create([
                        'type' => $method,
                        'product_id' => $detail->product_id,
                        'unit_cost' => $detail->{$method . '_unit_cost'},
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
