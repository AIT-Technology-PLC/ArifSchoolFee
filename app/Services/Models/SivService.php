<?php

namespace App\Services\Models;

use App\Actions\ApproveTransactionAction;
use App\Services\Inventory\InventoryOperationService;
use Illuminate\Support\Facades\DB;

class SivService
{
    public function subtract($siv, $user)
    {
        if (!userCompany()->canSivSubtract()) {
            return [false, 'Subtracting Siv is not allow. Contact your System Manager.'];
        }

        if (!$user->hasWarehousePermission('siv',
            $siv->sivDetails->pluck('warehouse_id')->toArray())) {
            return [false, 'You do not have permission to subtract from one or more of the warehouses.'];
        }

        if (!$siv->isApproved()) {
            return [false, 'This Siv is not approved yet.'];
        }

        if ($siv->isSubtracted()) {
            return [false, 'This Siv is already subtracted from inventory'];
        }

        $unavailableProducts = InventoryOperationService::unavailableProducts($siv->sivDetails);

        if ($unavailableProducts->isNotEmpty()) {
            return [false, $unavailableProducts];
        }

        DB::transaction(function () use ($siv) {
            InventoryOperationService::subtract($siv->sivDetails, $siv);

            $siv->subtract();
        });

        return [true, ''];
    }

    public function approveAndSubtract($siv, $user)
    {
        if (!userCompany()->canSivSubtract()) {
            return [false, 'Subtracting Siv is not allow. Contact your System Manager.'];
        }

        if (!$user->hasWarehousePermission('siv',
            $siv->sivDetails->pluck('warehouse_id')->toArray())) {
            return [false, 'You do not have permission to subtract from one or more of the warehouses.'];
        }

        if ($siv->isApproved()) {
            return [false, 'This Siv is already approved.'];
        }

        if ($siv->isSubtracted()) {
            return [false, 'This Siv is already subtracted from inventory'];
        }

        $unavailableProducts = InventoryOperationService::unavailableProducts($siv->sivDetails);

        if ($unavailableProducts->isNotEmpty()) {
            return [false, $unavailableProducts];
        }

        DB::transaction(function () use ($siv) {
            (new ApproveTransactionAction)->execute($siv);

            InventoryOperationService::subtract($siv->sivDetails, $siv);

            $siv->subtract();
        });

        return [true, ''];
    }
}
