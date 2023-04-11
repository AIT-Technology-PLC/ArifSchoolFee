<?php

namespace App\Services\Models;

use App\Actions\ApproveTransactionAction;
use App\Services\Inventory\InventoryOperationService;
use Illuminate\Support\Facades\DB;

class GrnService
{
    public function add($grn, $user)
    {
        if (!$user->hasWarehousePermission('add',
            $grn->grnDetails->pluck('warehouse_id')->toArray())) {
            return [false, 'You do not have permission to add to one or more of the warehouses.'];
        }

        if (!$grn->isApproved()) {
            return [false, 'This Goods Received Note is not approved yet.'];
        }

        if ($grn->isAdded()) {
            return [false, 'This Goods Received Note is already added to inventory.'];
        }

        DB::transaction(function () use ($grn) {
            InventoryOperationService::add($grn->grnDetails, $grn);

            $grn->add();
        });

        return [true, ''];
    }

    public function approveAndAdd($grn, $user)
    {
        if (!$user->hasWarehousePermission('add',
            $grn->grnDetails->pluck('warehouse_id')->toArray())) {
            return [false, 'You do not have permission to add to one or more of the warehouses.'];
        }

        if ($grn->isApproved()) {
            return [false, 'This Goods Received Note is already approved.'];
        }

        if ($grn->isAdded()) {
            return [false, 'This Goods Received Note is already added to inventory.'];
        }

        DB::transaction(function () use ($grn) {
            (new ApproveTransactionAction)->execute($grn);

            InventoryOperationService::add($grn->grnDetails, $grn);

            $grn->add();
        });

        return [true, ''];
    }
}