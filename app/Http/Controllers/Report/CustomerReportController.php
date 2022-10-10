<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterRequest;
use App\Reports\CustomerReport;
use App\Reports\SaleReport;

class CustomerReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Customer Report');
    }

    public function __invoke(FilterRequest $request)
    {
        abort_if(authUser()->cannot('Read Customer Report'), 403);

        $warehouses = authUser()->getAllowedWarehouses('transactions');

        $customerReport = new CustomerReport($request->validated('period'));

        $revenueReport = new SaleReport($request->validated('branches'), $request->validated('period'));

        return view('reports.customer', compact('warehouses', 'customerReport', 'revenueReport'));
    }
}
