<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterRequest;
use App\Reports\ReportSource;
use App\Reports\RevenueReport;
use App\Reports\TransactionReport;

class SalesPerformanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Sales Report');
    }

    public function __invoke(FilterRequest $request)
    {
        $warehouses = authUser()->getAllowedWarehouses('transactions');

        $revenueReport = new RevenueReport($request->validated('branches'), $request->validated('period'));

        $transactionReport = new TransactionReport(
            ReportSource::getSalesReportInput($request->validated('branches'), $request->validated('period'))
        );

        return view('reports.sales-performance', compact('revenueReport', 'transactionReport', 'warehouses'));
    }
}
