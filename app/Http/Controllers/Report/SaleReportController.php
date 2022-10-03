<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterRequest;
use App\Models\Employee;
use App\Reports\ReportSource;
use App\Reports\SaleReport;
use App\Reports\TransactionReport;

class SaleReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Sales Report');
        turnOffPreparedStatementEmulation();
        turnOffMysqlStictMode();
    }

    public function __invoke(FilterRequest $request)
    {
        abort_if(authUser()->cannot('Read Sale Report'), 403);

        $warehouses = authUser()->getAllowedWarehouses('transactions');

        $users = Employee::with('user:id,name')->get()->pluck('user')->sortBy('name');

        $revenueReport = new SaleReport($request->validated());

        $transactionReport = new TransactionReport(ReportSource::getSalesReportInput($request->validated()));

        return view('reports.sale', compact('revenueReport', 'transactionReport', 'warehouses', 'users'));
    }
}
