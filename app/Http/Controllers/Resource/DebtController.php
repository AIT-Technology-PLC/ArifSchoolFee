<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\DebtDatatable;
use App\DataTables\DebtSettlementDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDebtRequest;
use App\Http\Requests\UpdateDebtRequest;
use App\Models\Debt;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class DebtController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Debt Management');

        $this->authorizeResource(Debt::class, 'debt');
    }

    public function index(DebtDatatable $datatable)
    {
        $datatable->builder()->setTableId('debts-datatable')->orderBy(1, 'desc')->orderBy(2, 'desc');

        $totalDebts = Debt::count();

        $totalSettled = Debt::settled()->count();

        $totalPartiallySettled = Debt::partiallySettled()->count();

        $totalNotSettledAtAll = Debt::noSettlements()->count();

        $currentDebtBalance = Debt::sum('debt_amount') - Debt::sum('debt_amount_settled');

        $averageDebtSettlementDays = Debt::settled()->averageDebtSettlementDays();

        return $datatable->render('debts.index', compact('totalDebts', 'totalSettled', 'totalPartiallySettled', 'totalNotSettledAtAll', 'currentDebtBalance', 'averageDebtSettlementDays'));
    }

    public function create()
    {
        $currentDebtCode = nextReferenceNumber('debts');

        $suppliers = Supplier::validBusinessLicense()->orderBy('company_name')->get(['id', 'company_name']);

        return view('debts.create', compact('currentDebtCode', 'suppliers'));
    }

    public function store(StoreDebtRequest $request)
    {
        $supplier = Supplier::findOrFail($request->validated('supplier_id'));

        if ($supplier->hasReachedDebtLimit($request->validated('debt_amount'))) {
            return back()->withInput()->with('failedMessage', 'You can not exceed debt amount limit provided by this company');
        }

        $debt = DB::transaction(function () use ($request) {
            $debt = Debt::create($request->validated());

            $debt->createCustomFields($request->validated('customField'));

            return $debt;
        });

        return redirect()->route('debts.show', $debt->id);
    }

    public function show(Debt $debt, DebtSettlementDatatable $datatable)
    {
        $datatable->builder()->setTableId('debt-settlements-datatable');

        $debt->load(['purchase', 'supplier', 'customFieldValues.customField']);

        return $datatable->render('debts.show', compact('debt'));
    }

    public function edit(Debt $debt)
    {
        if ($debt->isSettled()) {
            return back()->with('failedMessage', 'Editing a fully settled debt is not allowed.');
        }

        if ($debt->purchase()->exists()) {
            return back()->with('failedMessage', 'Editing a debt that belongs to a Purchase is not allowed.');
        }

        $suppliers = Supplier::validBusinessLicense()->orderBy('company_name')->get(['id', 'company_name']);

        return view('debts.edit', compact('debt', 'suppliers'));
    }

    public function update(Debt $debt, UpdateDebtRequest $request)
    {
        if ($debt->isSettled()) {
            return redirect()->route('debts.show', $debt->id)
                ->with('failedMessage', 'Editing a fully settled debt is not allowed.');
        }

        if ($debt->purchase()->exists()) {
            return redirect()->route('debts.show', $debt->id)
                ->with('failedMessage', 'Editing a debt that belongs to a purchase is not allowed.');
        }

        if ($debt->supplier->hasReachedDebtLimit($request->validated('debt_amount'), $debt->id)) {
            return back()->with('failedMessage', 'You can not exceed debt amount limit provided by this company');
        }

        if ($request->validated('debt_amount') < $debt->debt_amount_settled) {
            return redirect()->route('debts.show', $debt->id)
                ->with('failedMessage', 'Debt amount cannot be less than debt settlements.');
        }

        DB::transaction(function () use ($debt, $request) {
            $debt->update($request->validated());

            $debt->createCustomFields($request->validated('customField'));
        });

        return redirect()->route('debts.show', $debt->id);
    }

    public function destroy(Debt $debt)
    {
        if ($debt->settlement_percentage > 0) {
            return back()->with('failedMessage', 'Deleting a debt that has one or more settlements is not allowed.');
        }

        $debt->forceDelete();

        return back()->with('deleted', 'Deleted successfully');
    }
}
