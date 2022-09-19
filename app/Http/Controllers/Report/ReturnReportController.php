<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterRequest;
use App\Reports\ReturnReport;

class ReturnReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Sales Report');
    }

    public function __invoke(FilterRequest $request)
    {
        abort_if(authUser()->cannot('Read Return Report'), 403);

        $warehouses = authUser()->getAllowedWarehouses('transactions');

        $returnReport = new ReturnReport($request->validated('branches'), $request->validated('period'));

        return view('reports.return', compact('warehouses', 'returnReport'));
    }
}
