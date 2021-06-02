<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use Illuminate\Http\Request;

class TransferSivController extends Controller
{
    public function __invoke(Transfer $transfer, Request $request)
    {
        $this->authorize('view', $transfer);

        $request->merge([
            'purpose' => 'Transfer',
            'ref_num' => $transfer->code,
            'siv' => $transfer->transferDetails->toArray(),
        ]);

        return redirect()->route('sivs.create')->withInput($request->all());
    }
}
