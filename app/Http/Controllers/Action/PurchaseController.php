<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Http\Controllers\Controller;
use App\Models\Grn;
use App\Models\Purchase;
use App\Notifications\PurchaseApproved;
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
        $this->authorize('close', $purchase);

        if ($purchase->isClosed()) {
            return back()->with('failedMessage', 'This purchase is already closed.');
        }

        $purchase->close();

        return back()->with('successMessage', 'Purchase closed and archived successfully.');
    }

    public function approve(Purchase $purchase, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $purchase);

        [$isExecuted, $message] = $action->execute($purchase, PurchaseApproved::class, 'Make Purchase');

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function purchase(Purchase $purchase)
    {
        $this->authorize('purchase', $purchase);

        $purchase->purchase();

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read Purchase', $purchase->warehouse_id, $purchase->createdBy),
            new PurchaseMade($purchase)
        );

        return back()->with('successMessage', 'This purchase is assigned as purchased successfully.');
    }
}
