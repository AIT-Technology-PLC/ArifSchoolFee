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
            $currentSivCode = (Siv::select('code')->latest()->first()->code) ?? 0;

            $siv = Siv::create([
                'code' => $this->prependCompanyId($currentSivCode + 1),
                'purpose' => 'Transfer',
                'ref_num' => $transfer->code,
                'issued_on' => today(),
                'created_by' => auth()->id,
                'updated_by' => auth()->id,
                'approved_by' => $transfer->approvedBy->id,
            ]);

            $sivDetails = $transfer->transferDetails()->get(['product_id', 'quantity'])->toArray();

            data_fill($sivDetails, '*.warehouse_id', $transfer->transferred_from);

            $siv->sivDetails()->createMany($sivDetails);

            Notification::send($this->notifiableUsers('Approve SIV'), new SivPrepared($siv));

            return $siv;
        });

        return redirect()->route('sivs.show', $siv->id);
    }
}
