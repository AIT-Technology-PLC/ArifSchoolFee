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
