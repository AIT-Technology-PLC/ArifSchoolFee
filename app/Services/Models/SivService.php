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

            if (!userCompany()->isConvertToSivAsApproved() && ($siv->sivable instanceof \App\Models\Gdn  || $siv->sivable instanceof \App\Models\Sale)) {
                if ($siv->sivable instanceof \App\Models\Gdn) {
                    $details = $siv->sivable->gdnDetails;
                } else {
                    $details = $siv->sivable->saleDetails;
                }

                foreach ($siv->sivDetails as $sivDetail) {
                    $modelDetails = $details->filter(function ($detail) use ($sivDetail) {
                        return $detail->product_id == $sivDetail->product_id &&
                        $detail->merchandise_batch_id == $sivDetail->merchandise_batch_id &&
                        $detail->warehouse_id == $sivDetail->warehouse_id &&
                        $detail->delivered_quantity < $detail->quantity;
                    });

                    if ($modelDetails->isNotEmpty()) {
                        $modelDetail = $modelDetails->first();
                        $modelDetail->delivered_quantity += $sivDetail->quantity;
                        $modelDetail->save();
                    }
                }
            }

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
}
