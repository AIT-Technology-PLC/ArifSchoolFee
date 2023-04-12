<?php

namespace App\Http\Controllers\Report;

use App\Exports\ExpenseReportExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\FilterRequest;
use App\Models\ExpenseCategory;
use App\Models\Tax;
use App\Reports\ExpenseReport;
use Maatwebsite\Excel\Facades\Excel;

class ExpenseReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Expense Report');
    }

    public function index(FilterRequest $request)
    {
        abort_if(authUser()->cannot('Read Expense Report'), 403);

        $warehouses = authUser()->getAllowedWarehouses('transactions');

        $taxes = Tax::get(['id', 'type']);

        $expenseCategories = ExpenseCategory::all();

        $expenseReport = new ExpenseReport($request->validated());

        return view('reports.expense', compact('expenseReport', 'warehouses', 'taxes', 'expenseCategories'));
    }

    public function export(FilterRequest $request)
    {
        abort_if(authUser()->cannot('Read Expense Report'), 403);

        $expenseReport = new ExpenseReport($request->validated());

        if (!$expenseReport->getExpenseTransactionCount) {
            return back()->with('failedMessage', 'No report available to be exported.');
        }

        return Excel::download(new ExpenseReportExport($expenseReport->query), 'Expenses Report.xlsx');
    }
}
