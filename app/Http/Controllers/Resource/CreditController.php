<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\CreditDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCreditRequest;
use App\Http\Requests\UpdateCreditRequest;
use App\Models\Credit;
use App\Models\Customer;
use App\Services\NextReferenceNumService;

class CreditController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Credit Management');

        $this->authorizeResource(Credit::class, 'credit');
    }

    public function index(CreditDatatable $datatable)
    {
        $datatable->builder()->orderBy(isFeatureEnabled('Gdn Management') ? 7 : 6, 'desc')->orderBy(1, 'desc');

        $totalCredits = Credit::count();

        $totalSettled = Credit::settled()->count();

        $totalPartiallySettled = Credit::partiallySettled()->count();

        $totalNotSettledAtAll = Credit::noSettlements()->count();

        return $datatable->render('credits.index', compact('totalCredits', 'totalSettled', 'totalPartiallySettled', 'totalNotSettledAtAll'));
    }

    public function create()
    {
        $customers = Customer::orderBy('company_name')->get(['id', 'company_name']);

        $currentCreditCode = NextReferenceNumService::table('credits');

        return view('credits.create', compact('customers', 'currentCreditCode'));
    }

    public function store(StoreCreditRequest $request)
    {
        $customer = Customer::findOrFail($request->validated()['customer_id']);

        if ($customer->hasReachedCreditLimit($request->validated()['credit_amount'])) {
            return back()->withInput()->with('failedMessage', 'The customer has exceeded the credit amount limit');
        }

        $credit = Credit::create($request->validated());

        return redirect()->route('credits.show', $credit->id);
    }

    public function edit(Credit $credit)
    {
        if ($credit->isSettled()) {
            return back()->with('failedMessage', 'Editing a fully settled credit is not allowed.');
        }

        if ($credit->gdn()->exists()) {
            return back()->with('failedMessage', 'Editing a credit that belongs to a delivery order is not allowed.');
        }

        $customers = Customer::orderBy('company_name')->get(['id', 'company_name']);

        return view('credits.edit', compact('credit', 'customers'));
    }

    public function update(Credit $credit, UpdateCreditRequest $request)
    {
        if ($credit->isSettled()) {
            return redirect()->route('credits.show', $credit->id)
                ->with('failedMessage', 'Editing a fully settled credit is not allowed.');
        }

        if ($credit->gdn()->exists()) {
            return redirect()->route('credits.show', $credit->id)
                ->with('failedMessage', 'Editing a credit that belongs to a delivery order is not allowed.');
        }

        if ($request->credit_amount < $credit->credit_amount_settled) {
            return redirect()->route('credits.show', $credit->id)
                ->with('failedMessage', 'Credit amount cannot be less than credit settlements.');
        }

        $credit->update($request->validated());

        return redirect()->route('credits.show', $credit->id);
    }

    public function show(Credit $credit)
    {
        $credit->load(['gdn', 'customer', 'creditSettlements']);

        return view('credits.show', compact('credit'));
    }

    public function destroy(Credit $credit)
    {
        if ($credit->settlement_percentage > 0) {
            return back()->with('failedMessage', 'Deleting a credit that has one or more settlements is not allowed.');
        }

        $credit->forceDelete();

        return back()->with('deleted', 'Deleted successfully');
    }
}
