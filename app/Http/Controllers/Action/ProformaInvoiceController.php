<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConvertProformaInvoiceRequest;
use App\Http\Requests\UpdateProformaInvoiceExpiresOnRequest;
use App\Models\Gdn;
use App\Models\ProformaInvoice;
use App\Models\Sale;
use App\Services\Models\ProformaInvoiceService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ProformaInvoiceController extends Controller
{
    private $proformaInvoiceService;

    public function __construct(ProformaInvoiceService $proformaInvoiceService)
    {
        $this->middleware('isFeatureAccessible:Proforma Invoice');
        
        $this->middleware('isFeatureAccessible:Sale Management')->only('convertToSale');
        
        $this->middleware('isFeatureAccessible:Gdn Management')->only('convertToGdn');

        $this->proformaInvoiceService = $proformaInvoiceService;
    }

    public function confirm(ProformaInvoice $proformaInvoice)
    {
        $this->authorize('confirm', $proformaInvoice);

        if ($proformaInvoice->isCancelled()) {
            return back()->with('failedMessage', 'This Proforma Invoice is cancelled');
        }

        if ($proformaInvoice->isConfirmed()) {
            return back()->with('failedMessage', 'This Proforma Invoice is already confirmed');
        }

        $proformaInvoice->confirm();

        return back();
    }

    public function cancel(ProformaInvoice $proformaInvoice)
    {
        $this->authorize('cancel', $proformaInvoice);

        if ($proformaInvoice->isConfirmed()) {
            return back()->with('failedMessage', 'Cancelling a confirmed Proforma Invoice is not allowed.');
        }

        if ($proformaInvoice->isCancelled()) {
            return back()->with('failedMessage', 'This Proforma Invoice is already cancelled');
        }

        $proformaInvoice->cancel();

        return back();
    }

    public function printed(ProformaInvoice $proformaInvoice)
    {
        $this->authorize('view', $proformaInvoice);

        if ($proformaInvoice->isCancelled()) {
            return back()->with('failedMessage', 'This Proforma Invoice is cancelled.');
        }

        $proformaInvoice->load(['proformaInvoiceDetails.product', 'proformaInvoiceDetails.merchandiseBatch', 'warehouse', 'customer', 'contact', 'company']);

        $havingCode = $proformaInvoice->proformaInvoiceDetails()->with('product')->get()->pluck('product')->pluck('code')->filter()->isNotEmpty();

        $havingBatch = $proformaInvoice->proformaInvoiceDetails()->with('merchandiseBatch')->get()->pluck('merchandiseBatch')->pluck('batch_no')->filter()->isNotEmpty();

        return Pdf::loadView('proforma-invoices.print', compact('proformaInvoice', 'havingCode', 'havingBatch'))->stream();
    }

    public function convertToGdn(ConvertProformaInvoiceRequest $request, ProformaInvoice $proformaInvoice)
    {
        $this->authorize('create', Gdn::class);

        [$isExecuted, $message, $gdn] = $this->proformaInvoiceService->convertToGdn($proformaInvoice, $request->validated());

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return redirect()->route('gdns.show', $gdn->id);
    }

    public function close(ProformaInvoice $proformaInvoice)
    {
        $this->authorize('close', $proformaInvoice);

        [$isExecuted, $message] = $this->proformaInvoiceService->close($proformaInvoice);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', 'Proforma Invoice closed and archived successfully.');
    }

    public function convertToSale(ConvertProformaInvoiceRequest $request, ProformaInvoice $proformaInvoice)
    {
        $this->authorize('create', Sale::class);

        [$isExecuted, $message, $sale] = $this->proformaInvoiceService->convertToSale($proformaInvoice, $request->validated());

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return redirect()->route('sales.show', $sale->id);
    }

    public function restore(UpdateProformaInvoiceExpiresOnRequest $request, ProformaInvoice $proformaInvoice)
    {
        $this->authorize('restore', $proformaInvoice);

        if ($proformaInvoice->isConfirmed()) {
            return back()->with('failedMessage', 'This Proforma Invoice is already confirmed');
        }

        if (!$proformaInvoice->isExpired()) {
            return back()->with('failedMessage', 'This proforma inovices is not expired yet.');
        }

        DB::transaction(function () use ($request, $proformaInvoice) {
            $proformaInvoice->expires_on = $request->validated('expires_on');

            $proformaInvoice->restore();
        });

        return redirect()->route('proforma-invoices.show', $proformaInvoice->id);
    }
}
