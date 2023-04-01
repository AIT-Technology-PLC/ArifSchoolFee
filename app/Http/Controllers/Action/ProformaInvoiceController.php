<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\Gdn;
use App\Models\ProformaInvoice;
use App\Models\Sale;
use App\Services\Models\ProformaInvoiceService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ProformaInvoiceController extends Controller
{
    private $proformaInvoiceService;

    public function __construct(ProformaInvoiceService $proformaInvoiceService)
    {
        $this->middleware('isFeatureAccessible:Proforma Invoice');

        $this->proformaInvoiceService = $proformaInvoiceService;
    }

    public function convert(ProformaInvoice $proformaInvoice)
    {
        $this->authorize('convert', $proformaInvoice);

        if ($proformaInvoice->isCancelled()) {
            return back()->with('failedMessage', 'This Proforma Invoice is cancelled');
        }

        if ($proformaInvoice->isConverted()) {
            return back()->with('failedMessage', 'This Proforma Invoice is already confirmed');
        }

        $proformaInvoice->convert();

        return back();
    }

    public function cancel(ProformaInvoice $proformaInvoice)
    {
        $this->authorize('cancel', $proformaInvoice);

        if ($proformaInvoice->isConverted()) {
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

    public function convertToGdn(Request $request, ProformaInvoice $proformaInvoice)
    {
        $this->authorize('create', Gdn::class);

        [$isExecuted, $message, $data] = $this->proformaInvoiceService->convertToGdn($proformaInvoice);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return redirect()->route('gdns.create')->withInput($request->merge($data)->all());
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

    public function convertToSale(Request $request, ProformaInvoice $proformaInvoice)
    {
        $this->authorize('create', Sale::class);

        [$isExecuted, $message, $data] = $this->proformaInvoiceService->convertToSale($proformaInvoice);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return redirect()->route('sales.create')->withInput($request->merge($data)->all());
    }
}