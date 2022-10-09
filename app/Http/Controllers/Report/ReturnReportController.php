<?php

namespace App\Http\Controllers\Report;

use App\Exports\ReturnReportExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\FilterRequest;
use App\Reports\ReturnReport;
use Maatwebsite\Excel\Facades\Excel;

class ReturnReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Sales Report');
    }

    public function index(FilterRequest $request)
    {
        abort_if(authUser()->cannot('Read Return Report'), 403);

        $warehouses = authUser()->getAllowedWarehouses('transactions');

        $returnReport = new ReturnReport($request->validated());

        return view('reports.return', compact('warehouses', 'returnReport'));
    }

    public function export(FilterRequest $request)
    {
        abort_if(authUser()->cannot('Read Return Report'), 403);

        $returnReport = new ReturnReport($request->validated());

        if (!$returnReport->getReturnsCount) {
            return back()->with('failedMessage', 'No report available to be exported.');
        }

        return Excel::download(new ReturnReportExport($returnReport->query), 'Sales Returns Report.xlsx');
    }
}
