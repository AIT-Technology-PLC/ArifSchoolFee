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
    public function execute($master, $purpose, $code, $issuedTo, $approvedBy, $details)
    {
        $siv = DB::transaction(function () use ($master, $purpose, $code, $issuedTo, $approvedBy, $details) {
            $siv = !is_null($master) ? $master->siv()->create([
                'code' => nextReferenceNumber('sivs'),
                'purpose' => $purpose,
                'ref_num' => $code,
                'issued_on' => now(),
                'issued_to' => $issuedTo,
                'approved_by' => userCompany()->isConvertToSivAsApproved() ? $approvedBy : null,
            ]) : Siv::create([
                'code' => nextReferenceNumber('sivs'),
                'purpose' => $purpose,
                'ref_num' => $code,
                'issued_on' => now(),
                'issued_to' => $issuedTo,
                'approved_by' => userCompany()->isConvertToSivAsApproved() ? $approvedBy : null,
            ]);

            $siv->sivDetails()->createMany($details->toArray());

            if (userCompany()->isConvertToSivAsApproved()) {
                Notification::send(
                    Notifiables::byPermissionAndWarehouse('Read SIV', $details->pluck('warehouse_id')),
                    new SivApproved($siv)
                );
            }

            if (! userCompany()->isConvertToSivAsApproved()) {
                Notification::send(
                    Notifiables::byNextActionPermission('Approve SIV'),
                    new SivPrepared($siv)
                );
            }

            return $siv;
        });

        return $siv;
    }
}
