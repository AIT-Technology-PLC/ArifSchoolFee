<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\Grn;
use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Purchase Management');
    }

    public function convertToGrn(Request $request, Purchase $purchase)
    {
        $this->authorize('view', $purchase);

        $this->authorize('create', Grn::class);

        if ($purchase->isClosed()) {
            return back()->with('failedMessage', 'This purchase is closed.');
        }

        $request->merge([
            'purchase_id' => $purchase->id,
            'supplier_id' => $purchase->supplier_id,
            'grn' => $purchase->purchaseDetails->toArray(),
        ]);

        return redirect()->route('grns.create')->withInput($request->all());
    }

    public function close(Purchase $purchase)
    {
        $this->authorize('create', $purchase);

        if ($purchase->isClosed()) {
            return back()->with('failedMessage', 'This purchase is already closed.');
        }

        $purchase->close();

        return back()->with('successMessage', 'Purchase closed and archived successfully.');
    }
}
