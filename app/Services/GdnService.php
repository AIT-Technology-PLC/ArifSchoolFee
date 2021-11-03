<?php

namespace App\Services;

use App\Services\InventoryOperationService;
use Illuminate\Support\Facades\DB;

class GdnService
{
    public function subtract($gdn)
    {
        if (!auth()->user()->hasWarehousePermission('sales',
            $gdn->gdnDetails->pluck('warehouse_id')->toArray())) {
            return [false, 'You do not have permission to sell from one or more of the warehouses.'];
        }

        if (!$gdn->isApproved()) {
            return [false, 'This Delivery Order is not approved yet.'];
        }

        if ($gdn->isSubtracted()) {
            return [false, 'This Delivery Order is already subtracted from inventory'];
        }

        $from = $gdn->reservation()->exists() ? 'reserved' : 'available';

        $unavailableProducts = InventoryOperationService::unavailableProducts($gdn->gdnDetails, $from);

        if ($unavailableProducts->isNotEmpty()) {
            return [false, $unavailableProducts];
        }

        DB::transaction(function () use ($gdn, $from) {
            InventoryOperationService::subtract($gdn->gdnDetails, $from);

            $gdn->subtract();
        });

        return [true, ''];
    }
}
