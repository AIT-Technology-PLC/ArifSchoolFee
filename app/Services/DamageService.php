<?php

namespace App\Services;

use App\Services\InventoryOperationService;
use Illuminate\Support\Facades\DB;

class DamageService
{
    public function subtract($damage)
    {
        if (!auth()->user()->hasWarehousePermission('subtract',
            $damage->damageDetails->pluck('warehouse_id')->toArray())) {
            return [false, 'You do not have permission to subtract from one or more of the warehouses.'];
        }

        if (!$damage->isApproved()) {
            return [false, 'This damage is not approved yet.'];
        }

        if ($damage->isSubtracted()) {
            return [false, 'This damage is already subtracted from inventory'];
        }

        $unavailableProducts = InventoryOperationService::unavailableProducts($damage->damageDetails);

        if ($unavailableProducts->isNotEmpty()) {
            return [false, $unavailableProducts];
        }

        DB::transaction(function () use ($damage) {
            InventoryOperationService::subtract($damage->damageDetails);

            $damage->subtract();
        });

        return [true, ''];
    }
}
