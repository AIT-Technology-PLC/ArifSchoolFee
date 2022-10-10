<?php

namespace App\Http\Controllers\Report;

use App\Exports\SaleReportExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\FilterRequest;
use App\Models\Employee;
use App\Reports\ReportSource;
use App\Reports\SaleReport;
use Maatwebsite\Excel\Facades\Excel;

class SaleReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Sales Report');
        turnOffPreparedStatementEmulation();
        turnOffMysqlStictMode();
    }

    public function index(FilterRequest $request)
    {
        abort_if(authUser()->cannot('Read Sale Report'), 403);

        $warehouses = authUser()->getAllowedWarehouses('transactions');

        $users = Employee::with('user:id,name')->get()->pluck('user')->sortBy('name');

        $saleReport = new SaleReport($request->validated());

        return view('reports.sale', compact('saleReport', 'warehouses', 'users'));
    }

    public function export(FilterRequest $request)
    {
        abort_if(authUser()->cannot('Read Sale Report'), 403);

        $saleReport = new SaleReport($request->validated());

        if (!$saleReport->getSalesCount) {
            return back()->with('failedMessage', 'No report available to be exported.');
        }

        return Excel::download(new SaleReportExport(ReportSource::getSalesReportInput($request->validated())), 'Sales Report.xlsx');
    }
}
