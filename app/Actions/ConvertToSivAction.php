<?php

namespace App\Actions;

use App\Models\Siv;
use App\Notifications\SivApproved;
use App\Notifications\SivPrepared;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ConvertToSivAction
{
    public function execute($model, $issuedTo, $details)
    {
        return DB::transaction(function () use ($model, $issuedTo, $details) {
            $siv = $model->siv()->create([
                'code' => nextReferenceNumber('sivs'),
                'issued_on' => now(),
                'issued_to' => $issuedTo,
                'approved_by' => userCompany()->isConvertToSivAsApproved() ? $model->approved_by : null,
            ]);

            $siv->sivDetails()->createMany($details->toArray());

            if (userCompany()->isConvertToSivAsApproved()) {
                if ($siv->sivable instanceof \App\Models\Gdn  || $siv->sivable instanceof \App\Models\Sale) {
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

                Notification::send(
                    Notifiables::byPermissionAndWarehouse('Read SIV', $details->pluck('warehouse_id')),
                    new SivApproved($siv)
                );
            }

            if (!userCompany()->isConvertToSivAsApproved()) {
                Notification::send(
                    Notifiables::byNextActionPermission('Approve SIV'),
                    new SivPrepared($siv)
                );
            }

            return $siv;
        });
    }
}
