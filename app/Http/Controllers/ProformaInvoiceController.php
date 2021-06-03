<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProformaInvoiceRequest;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProformaInvoice;
use App\Notifications\ProformaInvoicePrepared;
use App\Traits\NotifiableUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ProformaInvoiceController extends Controller
{
    use NotifiableUsers;

    public function __construct()
    {
        $this->authorizeResource(ProformaInvoice::class);
    }

    public function index()
    {
        $proformaInvoices = ProformaInvoice::companyProformaInvoices()
            ->with(['createdBy', 'updatedBy', 'convertedBy', 'customer'])->latest()->get();

        $totalProformaInvoices = $proformaInvoices->count();

        $totalConverted = $proformaInvoices->whereNotNull('converted_by')->count();

        $totalPending = $proformaInvoices->where('is_pending', 1)->count();

        $totalCancelled = $proformaInvoices->where('is_pending', 0)->whereNotNull('converted_by')->count();

        return view('proforma_invoices.index', compact('proformaInvoices', 'totalProformaInvoices',
            'totalConverted', 'totalPending', 'totalCancelled'));
    }

    public function create()
    {
        $products = Product::companyProducts()->orderBy('name')->get(['id', 'name']);

        $customers = Customer::companyCustomers()->orderBy('company_name')->get(['id', 'company_name']);

        return view('proforma_invoices.create', compact('products', 'customers'));
    }

    public function store(StoreProformaInvoiceRequest $request)
    {
        $proformaInvoice = DB::transaction(function () use ($request) {
            $proformaInvoice = ProformaInvoice::create($request->except('proformaInvoice'));

            $proformaInvoice->proformaInvoiceDetails()->createMany($request->proformaInvoice);

            Notification::send($this->notifiableUsers('Approve GDN'), new ProformaInvoicePrepared($proformaInvoice));

            return $proformaInvoice;
        });

        return redirect()->route('proforma-invoices.show', $proformaInvoice->id);
    }

    public function show(ProformaInvoice $proformaInvoice)
    {
        $proformaInvoice->load(['proformaInvoiceDetails.product', 'customer']);

        return $proformaInvoice;

        return view('proforma_invoices.show', compact('proformaInvoice'));
    }

    public function edit(ProformaInvoice $proformaInvoice)
    {
        $proformaInvoice->load(['proformaInvoiceDetails.product', 'customer']);

        $products = Product::companyProducts()->orderBy('name')->get(['id', 'name']);

        $customers = Customer::companyCustomers()->orderBy('company_name')->get(['id', 'company_name']);

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
