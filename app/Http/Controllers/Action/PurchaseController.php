<?php

namespace App\Http\Controllers\Action;

use App\Actions\RejectTransactionAction;
use App\Http\Controllers\Controller;
use App\Models\Grn;
use App\Models\Purchase;
use App\Notifications\PurchaseRejected;
use App\Services\Models\PurchaseService;
use Illuminate\Http\Request;

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

        [$isExecuted, $message] = $this->purchaseService->purchase($purchase);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

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

    public function reject(Purchase $purchase, RejectTransactionAction $action)
    {
        $this->authorize('reject', $purchase);

        if ($purchase->isApproved()) {
            return back()->with('failedMessage', 'You can not reject a purchase that is approved.');
        }

        if ($purchase->isRejected()) {
            return back()->with('failedMessage', 'This purchase is already rejected.');
        }

        [$isExecuted, $message] = $action->execute($purchase, new PurchaseRejected($purchase), 'Read Purchase');

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function cancel(Purchase $purchase)
    {
        $this->authorize('cancel', $purchase);

        [$isExecuted, $message] = $this->purchaseService->cancel($purchase);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function approveAndPurchase(Purchase $purchase)
    {
        $this->authorize('approve', $purchase);

        $this->authorize('purchase', $purchase);

        [$isExecuted, $message] = $this->purchaseService->approveAndPurchase($purchase);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', 'This purchase is assigned as purchased successfully.');
    }
}
