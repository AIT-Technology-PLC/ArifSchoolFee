<?php

namespace App\Services\Models;

use App\Actions\ApproveTransactionAction;
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

            if ($return->gdn->credit()->exists()) {
                if ($return->grandTotalPrice >= $return->gdn->credit()->value('credit_amount')) {
                    $return->gdn->credit()->forceDelete();
                } else {
                    $difference = $return->gdn->credit()->value('credit_amount') - $return->grandTotalPrice;

                    $return->gdn->credit()->update(
                        ['credit_amount' => $difference]
                    );
                }
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
        });

        return [true, ''];
    }
}
