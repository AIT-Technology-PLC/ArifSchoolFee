<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterRequest;
use App\Models\Warehouse;
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
        $warehouses = Warehouse::where('is_sales_store', 1)->get(['id', 'name']);

        $source = ReportSource::getSalesReportInput($request['branch'], $request['period']);

        $revenueReport = new RevenueReport($source);

        $transactionReport = new TransactionReport($source);

        return view('reports.sales-performance', compact('revenueReport', 'transactionReport', 'warehouses'));
    }
}
