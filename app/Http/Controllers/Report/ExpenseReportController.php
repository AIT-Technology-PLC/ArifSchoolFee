<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterRequest;
use App\Reports\ExpenseReport;

class ExpenseReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Expense Report');
    }

    public function __invoke(FilterRequest $request)
    {
        abort_if(authUser()->cannot('Read Expense Report'), 403);

        $warehouses = authUser()->getAllowedWarehouses('transactions');

        $expenseReport = new ExpenseReport($request->validated('branches'), $request->validated('period'));

        return view('reports.expense', compact('expenseReport', 'warehouses'));
    }
}
