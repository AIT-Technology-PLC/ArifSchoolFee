<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\Grn;
use App\Models\Purchase;
use App\Notifications\PurchaseMade;
use App\Services\Models\PurchaseService;
use App\Utilities\Notifiables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class PurchaseController extends Controller
{
    private $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->middleware('isFeatureAccessible:Purchase Management');

        $this->middleware('isFeatureAccessible:Debt Management')->only('convertToDebt');

        $this->purchaseService = $purchaseService;
    }

    public function convertToGrn(Request $request, Purchase $purchase)
    {
        $this->authorize('create', Grn::class);

        [$isExecuted, $message, $data] = $this->purchaseService->convertToGrn($purchase);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return redirect()->route('grns.create')->withInput($request->merge($data)->all());
    }

    public function close(Purchase $purchase)
    {
        $this->authorize('close', $purchase);

        if (!$purchase->isPurchased()) {
            return back()->with('failedMessage', 'This purchase is not yet purchased.');
        }

        if ($purchase->isClosed()) {
            return back()->with('failedMessage', 'This purchase is already closed.');
        }

        $purchase->close();

        return back()->with('successMessage', 'Purchase closed and archived successfully.');
    }

    public function approve(Purchase $purchase)
    {
        $this->authorize('approve', $purchase);

        [$isExecuted, $message] = $this->purchaseService->approve($purchase);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function purchase(Purchase $purchase)
    {
        $this->authorize('purchase', $purchase);

        if (!$purchase->isApproved()) {
            return back()->with('failedMessage', 'This purchase is not yet approved.');
        }

        if ($purchase->isPurchased()) {
            return back()->with('failedMessage', 'This purchase is already purchased.');
        }

        $purchase->purchase();

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read Purchase', $purchase->warehouse_id, $purchase->createdBy),
            new PurchaseMade($purchase)
        );

        return back()->with('successMessage', 'This purchase is assigned as purchased successfully.');
    }

    public function convertToDebt(Purchase $purchase)
    {
        $this->authorize('convertToDebt', $purchase);

        [$isExecuted, $message] = $this->purchaseService->convertToDebt($purchase);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return redirect()->route('debts.show', $purchase->debt->id);
    }
}
