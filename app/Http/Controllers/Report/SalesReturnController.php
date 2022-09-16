<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterRequest;
use App\Reports\ReportSource;
use App\Reports\SalesReturnReport;
use App\Reports\TransactionReport;

class SalesReturnController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Sales Report');
    }

    public function __invoke(FilterRequest $request)
    {
        abort_if(authUser()->cannot('Read Sales Return Report'), 403);

        $warehouses = authUser()->getAllowedWarehouses('transactions');

        $salesReturnReport = new SalesReturnReport(
            ReportSource::getSalesReturnReportInput($request->validated('branches'), $request->validated('period')));

        $transactionReport = new TransactionReport(
            ReportSource::getSalesReportInput($request->validated('branches'), $request->validated('period'))
        );

        return view('reports.sales-return', compact('warehouses', 'salesReturnReport', 'transactionReport'));
    }
}