<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\CreditDatatable;
use App\DataTables\CreditSettlementDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCreditRequest;
use App\Http\Requests\UpdateCreditRequest;
use App\Models\Credit;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class CreditController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Credit Management');

        $this->authorizeResource(Credit::class, 'credit');
    }

    public function index(CreditDatatable $datatable)
    {
        $datatable->builder()->setTableId('credits-datatable')->orderBy(1, 'desc')->orderBy(2, 'desc');

        $totalCredits = Credit::count();

        $totalSettled = Credit::settled()->count();

        $totalPartiallySettled = Credit::partiallySettled()->count();

        $totalNotSettledAtAll = Credit::noSettlements()->count();

        $currentCreditBalance = Credit::sum('credit_amount') - Credit::sum('credit_amount_settled');

        $averageCreditSettlementDays = Credit::settled()->averageCreditSettlementDays();

        return $datatable->render('credits.index', compact('totalCredits', 'totalSettled', 'totalPartiallySettled', 'totalNotSettledAtAll', 'currentCreditBalance', 'averageCreditSettlementDays'));
    }

    public function create()
    {
        $currentCreditCode = nextReferenceNumber('credits');

        return view('credits.create', compact('currentCreditCode'));
    }

    public function store(StoreCreditRequest $request)
    {
        $customer = Customer::findOrFail($request->validated('customer_id'));

        if ($customer->hasReachedCreditLimit($request->validated('credit_amount'))) {
            return back()->withInput()->with('failedMessage', 'The customer has exceeded the credit amount limit');
        }

        $credit = DB::transaction(function () use ($request) {
            $credit = Credit::create($request->validated());

            $credit->createCustomFields($request->validated('customField'));

            return $credit;
        });

        return redirect()->route('credits.show', $credit->id);
    }

    public function edit(Credit $credit)
    {
        if ($credit->isSettled()) {
            return back()->with('failedMessage', 'Editing a fully settled credit is not allowed.');
        }

        if (!is_null($credit->creditable_id)) {
            return back()->with('failedMessage', 'Editing a credit that belongs to a transaction is not allowed.');
        }

        return view('credits.edit', compact('credit'));
    }

    public function update(Credit $credit, UpdateCreditRequest $request)
    {
        if ($credit->isSettled()) {
            return redirect()->route('credits.show', $credit->id)
                ->with('failedMessage', 'Editing a fully settled credit is not allowed.');
        }

        if (!is_null($credit->creditable_id)) {
            return redirect()->route('credits.show', $credit->id)
                ->with('failedMessage', 'Editing a credit that belongs to a transaction is not allowed.');
        }

        if ($credit->customer->hasReachedCreditLimit($request->validated('credit_amount'), $credit->id)) {
            return back()->with('failedMessage', 'The customer has exceeded the credit amount limit');
        }

        if ($request->validated('credit_amount') < $credit->credit_amount_settled) {
            return redirect()->route('credits.show', $credit->id)
                ->with('failedMessage', 'Credit amount cannot be less than credit settlements.');
        }

        DB::transaction(function () use ($credit, $request) {
            $credit->update($request->validated());

            $credit->createCustomFields($request->validated('customField'));
        });

        return redirect()->route('credits.show', $credit->id);
    }

    public function show(Credit $credit, CreditSettlementDatatable $datatable)
    {
        $datatable->builder()->setTableId('credit-settlements-datatable');

        $credit->load(['creditable', 'customer', 'customFieldValues.customField']);

        return $datatable->render('credits.show', compact('credit'));
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
