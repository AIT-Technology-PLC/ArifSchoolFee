<?php

namespace App\Services;

use App\Services\InventoryOperationService;
use Illuminate\Support\Facades\DB;

class GrnService
{
    public function add($grn)
    {
        if (!auth()->user()->hasWarehousePermission('add',
            $grn->grnDetails->pluck('warehouse_id')->toArray())) {
            return back()->with('failedMessage', 'You do not have permission to add to one or more of the warehouses.');
        }

        if (!$grn->isApproved()) {
            return [false, 'This Goods Received Note is not approved yet.'];
        }

        if ($grn->isAdded()) {
            return [false, 'This Goods Received Note is already added to inventory.'];
        }

        DB::transaction(function () use ($grn) {
            InventoryOperationService::add($grn->grnDetails);

            $grn->add();
        });

        return [true, ''];
    }
}
