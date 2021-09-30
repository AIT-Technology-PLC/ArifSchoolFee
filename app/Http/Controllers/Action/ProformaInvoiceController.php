<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\ProformaInvoice;
use App\Traits\NotifiableUsers;

class ProformaInvoiceController extends Controller
{
    use NotifiableUsers;

    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Proforma Invoice');
    }

    public function convert(ProformaInvoice $proformaInvoice)
    {
        $this->authorize('convert', $proformaInvoice);

        if ($proformaInvoice->isConverted()) {
            return redirect()->back()->with('failedMessage', 'This Proforma Invoice has already been converted to DO');
        }

        if ($proformaInvoice->isCancelled()) {
            return redirect()->back()->with('failedMessage', 'This Proforma Invoice is cancelled');
        }

        $proformaInvoice->convert();

        return redirect()->back();
    }

    public function cancel(ProformaInvoice $proformaInvoice)
    {
        $this->authorize('cancel', $proformaInvoice);

        if ($proformaInvoice->isConverted()) {
            return redirect()->back()->with('failedMessage', 'This Proforma Invoice has been converted to DO');
        }

        if ($proformaInvoice->isCancelled()) {
            return redirect()->back()->with('failedMessage', 'This Proforma Invoice is already cancelled');
        }

        $proformaInvoice->cancel();

        return redirect()->back();
    }

    public function printed(ProformaInvoice $proformaInvoice)
    {
        $this->authorize('view', $proformaInvoice);

        $proformaInvoice->load(['proformaInvoiceDetails.product', 'customer', 'company']);

        return \PDF::loadView('proforma-invoices.print', compact('proformaInvoice'))
            ->setPaper('a4', 'portrait')
            ->stream();
    }
}
