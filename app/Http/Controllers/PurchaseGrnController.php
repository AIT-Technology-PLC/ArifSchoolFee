<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseGrnController extends Controller
{
    public function __invoke(Purchase $purchase, Request $request)
    {
        $this->authorize('view', $purchase);

        $request->merge([
            'purchase_id' => $purchase->id,
            'supplier_id' => $purchase->supplier_id,
            'grn' => $purchase->purchaseDetails->toArray(),
        ]);

        return redirect()->route('grns.create')->withInput($request->all());
    }
}
