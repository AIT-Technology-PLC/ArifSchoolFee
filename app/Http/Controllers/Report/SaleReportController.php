<?php

namespace App\Http\Controllers\Report;

use App\Exports\SaleReportExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\FilterRequest;
use App\Models\Employee;
use App\Models\Product;
use App\Reports\ReportSource;
use App\Reports\SaleReport;
use Barryvdh\DomPDF\Facade\Pdf;
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

        $products = Product::orderBy('name')->get();

        return view('reports.sale', compact('saleReport', 'warehouses', 'users', 'products'));
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

    public function print(FilterRequest $request)
    {
        abort_if(authUser()->cannot('Read Sale Report'), 403);

        $company = userCompany();

        $period = $request->validated('period');

        $saleReport = new SaleReport($request->validated());

        if (!$saleReport->getSalesCount) {
            return back()->with('failedMessage', 'No report available to be exported.');
        }

        return Pdf::loadView('reports.sale-print', compact('saleReport', 'period', 'company'))->stream();
    }
}
