<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProformaInvoiceRequest;
use App\Http\Requests\UpdateProformaInvoiceRequest;
use App\Models\Customer;
use App\Models\ProformaInvoice;
use App\Notifications\ProformaInvoicePrepared;
use App\Traits\NotifiableUsers;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ProformaInvoiceController extends Controller
{
    use NotifiableUsers;

    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Proforma Invoice');

        $this->authorizeResource(ProformaInvoice::class);
    }

    public function index()
    {
        $proformaInvoices = (new ProformaInvoice())->getAll()->load(['createdBy', 'updatedBy', 'convertedBy', 'customer']);

        $totalProformaInvoices = $proformaInvoices->count();

        $totalConverted = $proformaInvoices->whereNotNull('converted_by')->count();

        $totalPending = $proformaInvoices->where('is_pending', 1)->count();

        $totalCancelled = $proformaInvoices->where('is_pending', 0)->whereNull('converted_by')->count();

        return view('proforma-invoices.index', compact('proformaInvoices', 'totalProformaInvoices',
            'totalConverted', 'totalPending', 'totalCancelled'));
    }

    public function create()
    {
        $customers = Customer::companyCustomers()->orderBy('company_name')->get(['id', 'company_name']);

        $currentProformaInvoiceCode = (ProformaInvoice::select('code')->companyProformaInvoices()->latest()->first()->code) ?? 0;

        return view('proforma-invoices.create', compact('customers', 'currentProformaInvoiceCode'));
    }

    public function store(StoreProformaInvoiceRequest $request)
    {
        $proformaInvoice = DB::transaction(function () use ($request) {
            $proformaInvoice = ProformaInvoice::create($request->except('proformaInvoice'));

            $proformaInvoiceDetails = collect($request->proformaInvoice);

            $proformaInvoiceDetails = $proformaInvoiceDetails->map(function ($proformaInvoiceDetail) {
                if (!is_numeric($proformaInvoiceDetail['product_id'])) {
                    $proformaInvoiceDetail['custom_product'] = $proformaInvoiceDetail['product_id'];
                    Arr::forget($proformaInvoiceDetail, 'product_id');
                }

                return $proformaInvoiceDetail;
            });

            $proformaInvoice->proformaInvoiceDetails()->createMany($proformaInvoiceDetails);

            Notification::send($this->notifiableUsers('Approve GDN'), new ProformaInvoicePrepared($proformaInvoice));

            return $proformaInvoice;
        });

        return redirect()->route('proforma-invoices.show', $proformaInvoice->id);
    }

    public function show(ProformaInvoice $proformaInvoice)
    {
        $proformaInvoice->load(['proformaInvoiceDetails.product', 'customer', 'company']);

        return view('proforma-invoices.show', compact('proformaInvoice'));
    }

    public function edit(ProformaInvoice $proformaInvoice)
    {
        $proformaInvoice->load(['proformaInvoiceDetails.product', 'customer']);

        $customers = Customer::companyCustomers()->orderBy('company_name')->get(['id', 'company_name']);

        return view('proforma-invoices.edit', compact('proformaInvoice', 'customers'));
    }

    public function update(UpdateProformaInvoiceRequest $request, ProformaInvoice $proformaInvoice)
    {
        if (!$proformaInvoice->isPending()) {
            return redirect()->route('proforma-invoices.show', $proformaInvoice->id)
                ->with('failedMessage', 'Confirmed/Cancelled Proforma Inovices can not be edited.');
        }

        DB::transaction(function () use ($request, $proformaInvoice) {
            $proformaInvoice->update($request->except('proformaInvoice'));

            $proformaInvoiceDetails = collect($request->proformaInvoice);

            $proformaInvoiceDetails = $proformaInvoiceDetails->map(function ($proformaInvoiceDetail) {
                if (!is_numeric($proformaInvoiceDetail['product_id'])) {
                    $proformaInvoiceDetail['custom_product'] = $proformaInvoiceDetail['product_id'];
                    Arr::forget($proformaInvoiceDetail, 'product_id');
                }

                return $proformaInvoiceDetail;
            });

            $proformaInvoice->proformaInvoiceDetails
                ->each(function ($proformaInvoiceDetail, $index) use ($proformaInvoiceDetails) {
                    $proformaInvoiceDetail->update($proformaInvoiceDetails[$index]);
                });
        });

        return redirect()->route('proforma-invoices.show', $proformaInvoice->id);
    }

    public function destroy(ProformaInvoice $proformaInvoice)
    {
        if ($proformaInvoice->isConverted()) {
            abort(403);
        }

        if ($proformaInvoice->isCancelled() && !auth()->user()->can('Delete Cancelled Proforma Invoice')) {
            abort(403);
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

    public function printed(ProformaInvoice $proformaInvoice)
    {
        $this->authorize('view', $proformaInvoice);

        $proformaInvoice->load(['proformaInvoiceDetails.product', 'customer', 'company']);

        return \PDF::loadView('proforma-invoices.print', compact('proformaInvoice'))
            ->setPaper('a4', 'portrait')
            ->stream();
    }
}
