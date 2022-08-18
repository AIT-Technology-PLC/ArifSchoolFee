<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\DebitDatatable;
use App\DataTables\DebitSettlementDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDebitRequest;
use App\Http\Requests\UpdateDebitRequest;
use App\Models\Debit;
use App\Models\Supplier;

class DebitController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Debit Management');

        $this->authorizeResource(Debit::class, 'debit');
    }

    public function index(DebitDatatable $datatable)
    {
        $datatable->builder()->setTableId('debits-datatable')->orderBy(1, 'desc')->orderBy(2, 'desc');

        $totalDebits = Debit::count();

        $totalSettled = Debit::settled()->count();

        $totalPartiallySettled = Debit::partiallySettled()->count();

        $totalNotSettledAtAll = Debit::noSettlements()->count();

        $currentDebitBalance = Debit::sum('debit_amount') - Debit::sum('debit_amount_settled');

        $averageDebitSettlementDays = Debit::settled()->averageDebitSettlementDays();

        return $datatable->render('debits.index', compact('totalDebits', 'totalSettled', 'totalPartiallySettled', 'totalNotSettledAtAll', 'currentDebitBalance', 'averageDebitSettlementDays'));
    }

    public function create()
    {
        $currentDebitCode = nextReferenceNumber('debits');

        $suppliers = Supplier::orderBy('company_name')->get(['id', 'company_name']);

        return view('debits.create', compact('currentDebitCode', 'suppliers'));
    }

    public function store(StoreDebitRequest $request)
    {
        $debit = Debit::create($request->validated());

        return redirect()->route('debits.show', $debit->id);
    }

    public function show(Debit $debit, DebitSettlementDatatable $datatable)
    {
        $datatable->builder()->setTableId('debit-settlements-datatable');

        $debit->load(['purchase', 'supplier']);

        return $datatable->render('debits.show', compact('debit'));
    }

    public function edit(Debit $debit)
    {
        if ($debit->isSettled()) {
            return back()->with('failedMessage', 'Editing a fully settled debit is not allowed.');
        }

        if ($debit->purchase()->exists()) {
            return back()->with('failedMessage', 'Editing a debit that belongs to a Purchase is not allowed.');
        }

        return view('debits.edit', compact('debit'));
    }

    public function update(Debit $debit, UpdateDebitRequest $request)
    {
        if ($debit->isSettled()) {
            return redirect()->route('debits.show', $debit->id)
                ->with('failedMessage', 'Editing a fully settled debit is not allowed.');
        }

        if ($debit->gdn()->exists()) {
            return redirect()->route('debits.show', $debit->id)
                ->with('failedMessage', 'Editing a debit that belongs to a purchase is not allowed.');
        }

        if ($request->validated('debit_amount') < $debit->debit_amount_settled) {
            return redirect()->route('debits.show', $debit->id)
                ->with('failedMessage', 'Debit amount cannot be less than debit settlements.');
        }

        $debit->update($request->validated());

        return redirect()->route('debits.show', $debit->id);
    }

    public function destroy(Debit $debit)
    {
        if ($debit->settlement_percentage > 0) {
            return back()->with('failedMessage', 'Deleting a debit that has one or more settlements is not allowed.');
        }

        $debit->forceDelete();

        return back()->with('deleted', 'Deleted successfully');
    }
}
