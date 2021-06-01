<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProformaInvoice;
use App\Notifications\ProformaInvoiceExecuted;
use App\Traits\ApproveInventory;
use App\Traits\NotifiableUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ProformaInvoiceController extends Controller
{
    use NotifiableUsers, ApproveInventory;

    public function __construct()
    {
        $this->authorizeResource(ProformaInvoice::class, 'proforma-invoice');

        $this->permission = 'Execute Proforma Invoice';
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
        if ($proformaInvoice->isApproved() && !auth()->user()->can('Delete Approved SIV')) {
            return view('errors.permission_denied');
        }

        $proformaInvoice->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }

    public function execute(ProformaInvoice $proformaInvoice)
    {
        $this->authorize('execute', $proformaInvoice);

        if (!$proformaInvoice->isApproved()) {
            return redirect()->back()->with('failedMessage', 'This Proforma Invoice is not approved');
        }

        if ($proformaInvoice->isExecuted()) {
            return redirect()->back()->with('failedMessage', 'This Proforma Invoice is already executed');
        }

        DB::transaction(function () use ($proformaInvoice) {
            $proformaInvoice->execute();

            Notification::send(
                $this->notifiableUsers('Approve Proforma Invoice', $proformaInvoice->createdBy),
                new ProformaInvoiceExecuted($proformaInvoice)
            );
        });

        return redirect()->back();
    }
}
