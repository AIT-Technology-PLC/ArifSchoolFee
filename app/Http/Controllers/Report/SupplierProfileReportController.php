<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierProfileFilterRequest;
use App\Models\Supplier;
use App\Models\Tax;
use App\Reports\ExpenseReport;
use App\Reports\PurchaseReport;

class SupplierProfileReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Supplier Report');
    }

    public function __invoke(SupplierProfileFilterRequest $request, Supplier $supplier)
    {
        abort_if(authUser()->cannot('Read Supplier Profile Report'), 403);

        $warehouses = authUser()->getAllowedWarehouses('transactions');

        $purchaseReport = new PurchaseReport($request->validated());

        $lifeTimePurchaseReport = new PurchaseReport($request->safe()->only('supplier_id'));

        $lifetimeExpenseReport = new ExpenseReport($request->safe()->only('supplier_id'));

        $expenseReport = new ExpenseReport($request->validated());

        $totalDebtAmountProvided = $supplier->debts()->sum('debt_amount');

        $currentDebtBalance = $totalDebtAmountProvided - $supplier->debts()->sum('debt_amount_settled');

        $averageDebtSettlementDays = $supplier->debts()->settled()->averageDebtSettlementDays();

        $currentDebtLimit = $supplier->debt_amount_limit > 0 ? ($supplier->debt_amount_limit - $currentDebtBalance) : $supplier->debt_amount_limit;

        $taxes = Tax::get(['id', 'type']);

        return view('reports.supplier-profile', compact(
            'warehouses',
            'supplier',
            'purchaseReport',
            'totalDebtAmountProvided',
            'currentDebtBalance',
            'currentDebtLimit',
            'averageDebtSettlementDays',
            'lifeTimePurchaseReport',
            'lifetimeExpenseReport',
            'expenseReport',
            'taxes'
        ));
    }
}