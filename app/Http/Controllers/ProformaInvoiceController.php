<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProformaInvoice;
use App\Traits\NotifiableUsers;
use Illuminate\Http\Request;

class ProformaInvoiceController extends Controller
{
    use NotifiableUsers;

    public function __construct()
    {
        $this->authorizeResource(ProformaInvoice::class, 'proforma-invoice');
    }

    public function index()
    {
        //
    }

    public function create()
    {
        $products = Product::companyProducts()->orderBy('name')->get(['id', 'name']);

        $customers = Customer::companyCustomers()->orderBy('name')->get(['id', 'name']);

        return view('proforma_invoices.create', compact('products', 'customers'));
    }

    public function store(Request $request)
    {
        //
    }

    public function show(ProformaInvoice $proformaInvoice)
    {
        $proformaInvoice->load(['proformaInvoices.product', 'customer']);

        return view('proforma_invoices.show', compact('proformaInvoice'));

    }

    public function edit(ProformaInvoice $proformaInvoice)
    {
        $proformaInvoice->load(['proformaInvoices.product', 'customer']);

        $products = Product::companyProducts()->orderBy('name')->get(['id', 'name']);

        $customers = Customer::companyCustomers()->orderBy('name')->get(['id', 'name']);

        return view('proforma_invoices.edit', compact('proformaInvoice', 'products', 'customers'));
    }

    public function update(Request $request, ProformaInvoice $proformaInvoice)
    {
        //
    }

    public function destroy(ProformaInvoice $proformaInvoice)
    {
        if ($proformaInvoice->isConverted()) {
            return view('errors.permission_denied');
        }

        if ($proformaInvoice->isCancelled() && !auth()->user()->can('Delete Cancelled Proforma Invoice')) {
            return view('errors.permission_denied');
        }

        $proformaInvoice->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
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
}
