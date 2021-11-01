<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\Gdn;
use App\Models\ProformaInvoice;
use Illuminate\Http\Request;

class ProformaInvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Proforma Invoice');
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

        $proformaInvoice->load(['proformaInvoiceDetails.product', 'customer', 'company']);

        return \PDF::loadView('proforma-invoices.print', compact('proformaInvoice'))
            ->setPaper('a4', 'portrait')
            ->stream();
    }

    public function convertToGdn(Request $request, ProformaInvoice $proformaInvoice)
    {
        $this->authorize('view', $proformaInvoice);

        $this->authorize('create', Gdn::class);

        if ($proformaInvoice->isCancelled()) {
            return back()->with('failedMessage', 'This Proforma Invoice is cancelled.');
        }

        if ($proformaInvoice->isClosed()) {
            return back()->with('failedMessage', 'This Proforma Invoice is closed.');
        }

        $proformaInvoiceDetails = collect($proformaInvoice->proformaInvoiceDetails->toArray())
            ->map(function ($item) {
                $item['unit_price'] = $item['originalUnitPrice'];

                return $item;
            });

        $request->merge([
            'customer_id' => $proformaInvoice->customer_id ?? '',
            'discount' => number_format($proformaInvoice->discount * 100, 2),
            'gdn' => $proformaInvoiceDetails,
        ]);

        return redirect()->route('gdns.create')->withInput($request->all());
    }

    public function close(ProformaInvoice $proformaInvoice)
    {
        $this->authorize('convert', $proformaInvoice);

        if (!$proformaInvoice->isConverted()) {
            return back()->with('failedMessage', 'This Proforma Invoice is not confirmed yet.');
        }

        if ($proformaInvoice->isClosed()) {
            return back()->with('failedMessage', 'This Proforma Invoice is already closed.');
        }

        $proformaInvoice->close();

        return back()->with('successMessage', 'Proforma Invoice closed and archived successfully.');
    }
}
