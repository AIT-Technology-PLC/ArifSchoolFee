<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierProfileFilterRequest;
use App\Models\Supplier;
use App\Reports\ExpenseReport;
use App\Reports\SupplierReport;

class SupplierProfileReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Supplier Report');
    }

    public function __invoke(SupplierProfileFilterRequest $request, Supplier $supplier)
    {
        abort_if(authUser()->cannot('Read Supplier Profile Report'), 403);

        $supplierReport = new SupplierReport($request->validated());

        $totalDebtAmountProvided = $supplier->debts()->sum('debt_amount');

        $currentDebtBalance = $totalDebtAmountProvided - $supplier->debts()->sum('debt_amount_settled');

        $averageDebtSettlementDays = $supplier->debts()->settled()->averageDebtSettlementDays();

        $currentDebtLimit = $supplier->debt_amount_limit > 0 ? ($supplier->debt_amount_limit - $currentDebtBalance) : $supplier->debt_amount_limit;

        $lifeTimeSupplierReport = new SupplierReport($request->validated('supplier_id'));

        $lifetimeExpenseReport = new ExpenseReport($request->validated('supplier_id'));

        $expenseReport = new ExpenseReport($request->validated());

        return view('reports.supplier-profile', compact(
            'supplier',
            'supplierReport',
            'totalDebtAmountProvided',
            'currentDebtBalance',
            'currentDebtLimit',
            'averageDebtSettlementDays',
            'lifeTimeSupplierReport',
            'lifetimeExpenseReport',
            'expenseReport'
        ));
    }
}