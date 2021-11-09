<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\CreditDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCreditRequest;
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
        $datatable->builder()->orderBy(7, 'desc');

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
        $credit = Credit::create($request->validated());

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
