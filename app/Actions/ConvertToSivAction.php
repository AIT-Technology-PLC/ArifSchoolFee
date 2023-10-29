<?php

namespace App\Actions;

use App\Notifications\SivApproved;
use App\Notifications\SivPrepared;
use App\Services\Models\SivService;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ConvertToSivAction
{
    public function execute($model, $issuedTo, $details)
    {
        return DB::transaction(function () use ($model, $issuedTo, $details) {
            $siv = $model->sivs()->create([
                'code' => nextReferenceNumber('sivs'),
                'issued_on' => now(),
                'issued_to' => $issuedTo,
                'approved_by' => userCompany()->isConvertToSivAsApproved() ? $model->approved_by : null,
            ]);

            $siv->sivDetails()->createMany($details->toArray());

            if (userCompany()->isConvertToSivAsApproved()) {
                (new SivService)->deliverSiv($siv);

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
