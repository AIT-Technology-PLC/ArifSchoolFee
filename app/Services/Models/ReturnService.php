<?php

namespace App\Services\Models;

use App\Actions\ApproveTransactionAction;
use App\Models\GdnDetail;
use App\Notifications\ReturnApproved;
use App\Services\Inventory\InventoryOperationService;
use Illuminate\Support\Facades\DB;

class ReturnService
{
    public function approve($return)
    {
        return DB::transaction(function () use ($return) {
            [$isExecuted, $message] = (new ApproveTransactionAction)->execute($return, ReturnApproved::class, 'Make Return');

            if (!$isExecuted) {
                return [$isExecuted, $message];
            }

            return [true, $message];
        });
    }

    public function add($return, $user)
    {
        if (!$user->hasWarehousePermission('add',
            $return->returnDetails->pluck('warehouse_id')->toArray())) {
            return [false, 'You do not have permission to add to one or more of the warehouses.'];
        }

        if (!$return->isApproved()) {
            return [false, 'This transaction is not approved yet.'];
        }

        if ($return->isAdded()) {
            return [false, 'This transaction is already added to inventory.'];
        }

        DB::transaction(function () use ($return) {
            InventoryOperationService::add($return->returnDetails, $return);

            $return->add();

            foreach ($return->returnDetails as $returnDetail) {
                $gdnDetails = GdnDetail::query()
                    ->when(!is_null($return->customer_id), fn($q) => $q->whereRelation('gdn', 'customer_id', $return->customer_id))
                    ->when(!is_null($return->gdn_id), fn($q) => $q->where('gdn_id', $return->gdn_id))
                    ->where('product_id', $returnDetail->product_id)
                    ->whereColumn('quantity', '>', 'returned_quantity')
                    ->get();

                foreach ($gdnDetails as $gdnDetail) {
                    if ($returnDetail->quantity <= 0) {
                        break;
                    }

                    $allowedQuantity = $gdnDetail->quantity - $gdnDetail->returned_quantity;
                    if ($allowedQuantity == 0) {
                        continue;
                    }

                    if ($allowedQuantity >= $returnDetail->quantity) {
                        $gdnDetail->returned_quantity += $returnDetail->quantity;

                        $gdnDetail->save();
                        break;
                    }

                    if ($allowedQuantity < $returnDetail->quantity) {
                        $gdnDetail->returned_quantity += $allowedQuantity;
                        $returnDetail->quantity -= $allowedQuantity;

                        $gdnDetail->save();
                    }
                }
            }

            if ($return->gdn?->payment_type == 'Deposits') {
                $return->gdn->customer->incrementBalance($return->grandTotalPrice);
            }

            if ($return->gdn?->credit && $return->grandTotalPrice >= $return->gdn->credit->credit_amount) {
                $return->gdn->credit->forceDelete();
            }

            if ($return->gdn?->credit && $return->gdn->credit->credit_amount > $return->grandTotalPrice) {
                $difference = $return->gdn->credit->credit_amount - $return->grandTotalPrice;

                $return->gdn->credit->credit_amount = $difference;

                $return->gdn->credit->save();
            }
        });

        return [true, ''];
    }
}
