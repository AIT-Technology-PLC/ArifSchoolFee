<?php

namespace App\Services\Models;

use App\Actions\ApproveTransactionAction;
use App\Notifications\SivApproved;
use App\Services\Inventory\InventoryOperationService;
use Illuminate\Support\Facades\DB;

class SivService
{
    public function approve($siv)
    {
        if (!authUser()->hasWarehousePermission('siv',
            $siv->sivDetails->pluck('warehouse_id')->toArray())) {
            return [false, 'You do not have permission to approve in one or more of the warehouses.'];
        }

        return DB::transaction(function () use ($siv) {
            [$isExecuted, $message] = (new ApproveTransactionAction)->execute($siv, SivApproved::class, 'Read SIV');

            $this->deliverSiv($siv);

            if (!$isExecuted) {
                return [$isExecuted, $message];
            }

            return [true, $message];
        });
    }

    public function subtract($siv, $user)
    {
        if (!userCompany()->canSivSubtract()) {
            return [false, 'Subtracting Siv is not allow. Contact your System Manager.'];
        }

        if ($siv->isAssociated()) {
            return [false, 'SIVs issued from other transactions can not be subtracted.'];
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

        if ($siv->isAssociated()) {
            return [false, 'SIVs issued from other transactions can not be subtracted.'];
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

    public function deliverSiv($siv)
    {
        if ($siv->sivable?->isFullyDelivered()) {
            return;
        }

        foreach ($siv->sivDetails as $sivDetail) {
            $modelDetails = $siv->sivable->details()
                ->where('product_id', $sivDetail->product_id)
                ->where('merchandise_batch_id', $sivDetail->merchandise_batch_id)
                ->where('warehouse_id', $sivDetail->warehouse_id)
                ->filter(fn($detail) => $detail->quantity > $detail->delivered_quantity);

            if ($modelDetails->isNotEmpty()) {
                $modelDetail = $modelDetails->first();
                $modelDetail->delivered_quantity += $sivDetail->quantity;
                $modelDetail->save();
            }
        }
    }
}
