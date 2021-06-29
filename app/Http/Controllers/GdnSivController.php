<?php

namespace App\Http\Controllers;

use App\Models\Gdn;
use App\Models\Siv;
use App\Notifications\SivPrepared;
use App\Traits\NotifiableUsers;
use App\Traits\PrependCompanyId;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class GdnSivController extends Controller
{
    use PrependCompanyId, NotifiableUsers;

    public function __invoke(Gdn $gdn)
    {
        $this->authorize('view', $gdn);

        $this->authorize('create', Siv::class);

        $siv = DB::transaction(function () use ($gdn) {
            $currentSivCode = (Siv::select('code')->companySiv()->latest()->first()->code) ?? 0;

            $siv = Siv::create([
                'code' => $this->prependCompanyId($currentSivCode + 1),
                'purpose' => 'DO',
                'ref_num' => $gdn->code,
                'issued_on' => today(),
                'issued_to' => $gdn->customer->company_name ?? '',
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
                'approved_by' => $gdn->approvedBy->id,
                'company_id' => userCompany()->id,
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
