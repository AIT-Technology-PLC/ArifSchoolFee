<?php

namespace App\Http\Controllers;

use App\Models\Gdn;
use App\Models\Siv;
use Illuminate\Support\Facades\DB;

class GdnSivController extends Controller
{
    public function __invoke(Gdn $gdn)
    {
        $this->authorize('view', $gdn);

        $this->authorize('create', Siv::class);

        $siv = DB::transaction(function () use ($gdn) {
            $currentSivCode = (Siv::select('code')->companySiv()->latest()->first()->code) ?? 0;

            $siv = Siv::create([
                'code' => $currentSivCode + 1,
                'purpose' => 'DO',
                'ref_num' => $gdn->code,
                'issued_on' => today(),
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
                'company_id' => userCompany()->id,
            ]);

            $sivDetails = $gdn->gdnDetails()
                ->get(['product_id', 'warehouse_id', 'quantity'])
                ->toArray();

            $siv->sivDetails()->createMany($sivDetails);

            return $siv;
        });

        return redirect()->route('sivs.show', $siv->id);
    }
}
