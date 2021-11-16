<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Http\Controllers\Controller;
use App\Models\Credit;
use App\Models\Gdn;
use App\Models\Siv;
use App\Notifications\GdnApproved;
use App\Notifications\GdnSubtracted;
use App\Services\GdnService;
use Illuminate\Support\Facades\Notification;

class GdnController extends Controller
{
    private $gdnService;

    public function __construct(GdnService $gdnService)
    {
        $this->middleware('isFeatureAccessible:Gdn Management');

        $this->middleware('isFeatureAccessible:Credit Management')->only('convertToCredit');

        $this->gdnService = $gdnService;
    }

    public function approve(Gdn $gdn, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $gdn);

        if (!auth()->user()->hasWarehousePermission('sales',
            $gdn->gdnDetails->pluck('warehouse_id')->toArray())) {
            return back()->with('failedMessage', 'You do not have permission to approve in one or more of the warehouses.');
        }

        [$isExecuted, $message] = $action->execute($gdn, GdnApproved::class, 'Subtract GDN');

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function printed(Gdn $gdn)
    {
        $this->authorize('view', $gdn);

        if (!$gdn->isApproved()) {
            return back()->with('failedMessage', 'This Delivery Order is not approved yet.');
        }

        $gdn->load(['gdnDetails.product', 'customer', 'company', 'createdBy', 'approvedBy']);

        return \PDF::loadView('gdns.print', compact('gdn'))
            ->setPaper('a4', 'portrait')
            ->stream();
    }

    public function convertToSiv(Gdn $gdn)
    {
        $this->authorize('view', $gdn);

        $this->authorize('create', Siv::class);

        [$isExecuted, $message, $siv] = $this->gdnService->convertToSiv($gdn);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return redirect()->route('sivs.show', $siv->id);
    }

    public function subtract(Gdn $gdn)
    {
        $this->authorize('subtract', $gdn);

        [$isExecuted, $message] = $this->gdnService->subtract($gdn);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(notifiables('Approve GDN', $gdn->createdBy), new GdnSubtracted($gdn));

        return back();
    }

    public function close(Gdn $gdn)
    {
        $this->authorize('approve', $gdn);

        [$isExecuted, $message] = $this->gdnService->close($gdn);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', 'Delivery Order closed and archived successfully.');
    }

    public function convertToCredit(Gdn $gdn)
    {
        $this->authorize('create', Credit::class);

        [$isExecuted, $message] = $this->gdnService->convertToCredit($gdn);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return redirect()->route('credits.show', $gdn->credit->id);
    }
}
