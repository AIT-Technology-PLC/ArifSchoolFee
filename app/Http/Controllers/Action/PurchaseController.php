<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\Grn;
use App\Models\Purchase;
use App\Services\PurchaseService;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    private $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->middleware('isFeatureAccessible:Purchase Management');

        $this->purchaseService = $purchaseService;
    }

    public function convertToGrn(Request $request, Purchase $purchase)
    {
        $this->authorize('view', $purchase);

        $this->authorize('create', Grn::class);

        [$isExecuted, $message, $data] = $this->purchaseService->convertToGrn($purchase);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return redirect()->route('grns.create')->withInput($request->merge($data)->all());
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
