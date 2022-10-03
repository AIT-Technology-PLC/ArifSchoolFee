<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterRequest;
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

        $users = ReportSource::getSalesReportInput($request->validated('branches'), $request->validated('period'))['master']
            ->get(['user_name', 'created_by'])
            ->unique();

        $revenueReport = new SaleReport($request->validated('branches'), $request->validated('period'), $request->validated('user_id'));

        $transactionReport = new TransactionReport(
            ReportSource::getSalesReportInput($request->validated('branches'), $request->validated('period'), $request->validated('user_id'))
        );

        return view('reports.sale', compact('revenueReport', 'transactionReport', 'warehouses', 'users'));
    }
}
