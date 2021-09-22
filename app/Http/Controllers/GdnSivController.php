<?php

namespace App\Http\Controllers;

use App\Models\Gdn;
use App\Models\Siv;
use App\Notifications\SivPrepared;
use App\Traits\NotifiableUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class GdnSivController extends Controller
{
    use NotifiableUsers;

    public function __invoke(Gdn $gdn)
    {
        $this->authorize('view', $gdn);

        $this->authorize('create', Siv::class);

        $siv = DB::transaction(function () use ($gdn) {
            $siv = Siv::create([
                'code' => Siv::byBranch()->max('code') + 1,
                'purpose' => 'DO',
                'ref_num' => $gdn->code,
                'issued_on' => today(),
                'issued_to' => $gdn->customer->company_name ?? '',
                'approved_by' => $gdn->approvedBy->id,
            ]);

            $sivDetails = $gdn->gdnDetails()
                ->get(['product_id', 'warehouse_id', 'quantity'])
                ->toArray();

            $siv->sivDetails()->createMany($sivDetails);

            Notification::send($this->notifiableUsers('Approve SIV'), new SivPrepared($siv));

            return $siv;
        });

        return redirect()->route('sivs.show', $siv->id);
    }
}
