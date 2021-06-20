<?php

namespace App\Http\Controllers;

use App\Models\Siv;
use App\Models\Transfer;
use Illuminate\Support\Facades\DB;

class TransferSivController extends Controller
{
    public function __invoke(Transfer $transfer)
    {
        $this->authorize('view', $transfer);

        $this->authorize('create', Siv::class);

        $siv = DB::transaction(function () use ($transfer) {
            $currentSivCode = (Siv::select('code')->companySiv()->latest()->first()->code) ?? 0;

            $siv = Siv::create([
                'code' => $currentSivCode + 1,
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

            return $siv;
        });

        return redirect()->route('sivs.show', $siv->id);
    }
}
