<?php
namespace App\Actions;

use App\Models\Siv;
use App\Notifications\SivPrepared;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ConvertToSivAction
{
    public function execute($purpose, $code, $issuedTo, $approvedBy, $details)
    {
        $siv = DB::transaction(function () use ($purpose, $code, $issuedTo, $approvedBy, $details) {
            $siv = Siv::create([
                'code' => nextReferenceNumber('sivs'),
                'purpose' => $purpose,
                'ref_num' => $code,
                'issued_on' => now(),
                'issued_to' => $issuedTo,
                'approved_by' => userCompany()->isConvertToSivAsApproved() ? $approvedBy : null,
            ]);

            $siv->sivDetails()->createMany($details);

            Notification::send(Notifiables::byNextActionPermission('Approve SIV'), new SivPrepared($siv));

            return $siv;
        });

        return $siv;
    }
}
