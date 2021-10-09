<?php
namespace App\Actions;

use App\Models\Siv;
use App\Notifications\SivPrepared;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ConvertToSivAction
{
    public function execute($purpose, $code, $issuedTo, $approvedBy, $details)
    {
        $siv = DB::transaction(function () use ($purpose, $code, $issuedTo, $approvedBy, $details) {
            $siv = Siv::create([
                'code' => Siv::byBranch()->max('code') + 1,
                'purpose' => $purpose,
                'ref_num' => $code,
                'issued_on' => now(),
                'issued_to' => $issuedTo,
                'approved_by' => $approvedBy,
            ]);

            $siv->sivDetails()->createMany($details);

            Notification::send(notifiables('Approve SIV'), new SivPrepared($siv));

            return $siv;
        });

        return $siv;
    }
}
