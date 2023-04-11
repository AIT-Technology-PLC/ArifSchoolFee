<?php

namespace App\Services\Models;

use App\Actions\ApproveTransactionAction;
use App\Services\Inventory\InventoryOperationService;
use Illuminate\Support\Facades\DB;

class AdjustmentService
{
    public function adjust($adjustment, $user)
    {
        if (!$user->hasWarehousePermission('adjustment',
            $adjustment->adjustmentDetails->pluck('warehouse_id')->toArray())) {
            return [false, 'You do not have adjustment permission to adjust in one or more of the warehouses.'];
        }

        if (!$adjustment->isApproved()) {
            return [false, 'This Adjustment is not approved.'];
        }

        if ($adjustment->isAdjusted()) {
            return [false, 'This Adjustment is already executed.'];
        }

        $unavailableProducts = InventoryOperationService::unavailableProducts($this->loadOnlySubtracts($adjustment));

        if ($unavailableProducts->isNotEmpty()) {
            return [false, $unavailableProducts];
        }

        DB::transaction(function () use ($adjustment) {
            InventoryOperationService::subtract($this->loadOnlySubtracts($adjustment), $adjustment);

            InventoryOperationService::add($this->loadOnlyAdds($adjustment), $adjustment);

            $adjustment->adjust();
        });

        return [true, ''];
    }

    public function loadOnlySubtracts($adjustment)
    {
        return $adjustment->adjustmentDetails()->where('is_subtract', 1)->get();
    }

    public function loadOnlyAdds($adjustment)
    {
        return $adjustment->adjustmentDetails()->where('is_subtract', 0)->get();
    }

    public function approveAndAdjust($adjustment, $user)
    {
        if (!$user->hasWarehousePermission('adjustment',
            $adjustment->adjustmentDetails->pluck('warehouse_id')->toArray())) {
            return [false, 'You do not have adjustment permission to adjust in one or more of the warehouses.'];
        }

        if ($adjustment->isApproved()) {
            return [false, 'This Adjustment is already approved.'];
        }

        if ($adjustment->isAdjusted()) {
            return [false, 'This Adjustment is already executed.'];
        }

        $unavailableProducts = InventoryOperationService::unavailableProducts($this->loadOnlySubtracts($adjustment));

        if ($unavailableProducts->isNotEmpty()) {
            return [false, $unavailableProducts];
        }

        DB::transaction(function () use ($adjustment) {
            (new ApproveTransactionAction)->execute($adjustment);

            InventoryOperationService::subtract($this->loadOnlySubtracts($adjustment), $adjustment);

            InventoryOperationService::add($this->loadOnlyAdds($adjustment), $adjustment);

            $adjustment->adjust();
        });

        return [true, ''];
    }
}