<?php

namespace App\Http\Controllers;

use App\Models\Siv;
use App\Models\Transfer;
use App\Notifications\SivPrepared;
use App\Traits\NotifiableUsers;
use App\Traits\PrependCompanyId;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class TransferSivController extends Controller
{
    use PrependCompanyId, NotifiableUsers;

    public function __invoke(Transfer $transfer)
    {
        $this->authorize('view', $transfer);

        $this->authorize('create', Siv::class);

        $siv = DB::transaction(function () use ($transfer) {
            $currentSivCode = (Siv::select('code')->companySiv()->latest()->first()->code) ?? 0;

            $siv = Siv::create([
                'code' => $this->prependCompanyId($currentSivCode + 1),
                'purpose' => 'Transfer',
                'ref_num' => $transfer->code,
                'issued_on' => today(),
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
                'approved_by' => $transfer->approvedBy->id,
                'company_id' => userCompany()->id,
            ]);

            $sivDetails = $transfer->transferDetails()
                ->get(['product_id', 'warehouse_id', 'quantity'])
                ->toArray();

            $siv->sivDetails()->createMany($sivDetails);

            Notification::send($this->notifiableUsers('Approve SIV'), new SivPrepared($siv));

            return $siv;
        });

        return redirect()->route('sivs.show', $siv->id);
    }
}
