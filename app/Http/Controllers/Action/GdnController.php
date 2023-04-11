<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImportFileRequest;
use App\Models\Gdn;
use App\Models\Sale;
use App\Models\Siv;
use App\Notifications\GdnSubtracted;
use App\Services\Models\GdnService;
use App\Utilities\Notifiables;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Notification;

class GdnController extends Controller
{
    private $gdnService;

    public function __construct(GdnService $gdnService)
    {
        $this->middleware('isFeatureAccessible:Gdn Management');

        $this->middleware('isFeatureAccessible:Credit Management')->only('convertToCredit');

        $this->middleware('isFeatureAccessible:Siv Management')->only('convertToSiv');

        $this->middleware('isFeatureAccessible:Sale Management')->only('convertToSale');

        $this->gdnService = $gdnService;
    }

    public function approve(Gdn $gdn)
    {
        $this->authorize('approve', $gdn);

        [$isExecuted, $message] = $this->gdnService->approve($gdn);

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

        if ($gdn->isCancelled()) {
            return back()->with('failedMessage', 'This Delivery Order is cancelled.');
        }

        $gdn->load(['gdnDetails.product', 'customer', 'contact', 'warehouse', 'company', 'createdBy', 'approvedBy']);

        $havingCode = $gdn->gdnDetails()->with('product')->get()->pluck('product')->pluck('code')->filter()->isNotEmpty();

        $havingBatch = $gdn->gdnDetails()->with('merchandiseBatch')->get()->pluck('merchandiseBatch')->pluck('batch_no')->filter()->isNotEmpty();

        return Pdf::loadView('gdns.print', compact('gdn', 'havingCode', 'havingBatch'))->stream();
    }

    public function convertToSiv(Gdn $gdn)
    {
        $this->authorize('create', Siv::class);

        [$isExecuted, $message, $siv] = $this->gdnService->convertToSiv($gdn, authUser());

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return redirect()->route('sivs.show', $siv->id);
    }

    public function subtract(Gdn $gdn)
    {
        $this->authorize('subtract', $gdn);

        [$isExecuted, $message] = $this->gdnService->subtract($gdn, authUser());

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read GDN', $gdn->gdnDetails->pluck('warehouse_id'), $gdn->createdBy),
            new GdnSubtracted($gdn)
        );

        return back();
    }

    public function close(Gdn $gdn)
    {
        $this->authorize('close', $gdn);

        [$isExecuted, $message] = $this->gdnService->close($gdn);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', 'Delivery Order closed and archived successfully.');
    }

    public function convertToCredit(Gdn $gdn)
    {
        $this->authorize('convertToCredit', $gdn);

        [$isExecuted, $message] = $this->gdnService->convertToCredit($gdn);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return redirect()->route('credits.show', $gdn->credit->id);
    }

    public function import(UploadImportFileRequest $importFileRequest)
    {
        $this->authorize('import', Gdn::class);

        $validatedData = $this->gdnService->importValidatedData($importFileRequest->validated('file'));

        [$isExecuted, $message, $gdn] = $this->gdnService->import($validatedData);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return redirect()->route('gdns.show', $gdn->id);
    }

    public function convertToSale(Gdn $gdn)
    {
        $this->authorize('create', Sale::class);

        [$isExecuted, $message, $sale] = $this->gdnService->convertToSale($gdn, authUser());

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return redirect()->route('sales.show', $sale->id);
    }

    public function cancel(Gdn $gdn)
    {
        $this->authorize('cancel', $gdn);

        [$isExecuted, $message] = $this->gdnService->cancel($gdn);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function approveAndSubtract(Gdn $gdn)
    {
        $this->authorize('approve', $gdn);

        $this->authorize('subtract', $gdn);

        [$isExecuted, $message] = $this->gdnService->approveAndSubtract($gdn, authUser());

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read GDN', $gdn->gdnDetails->pluck('warehouse_id'), $gdn->createdBy),
            new GdnSubtracted($gdn)
        );

        return back();
    }
}