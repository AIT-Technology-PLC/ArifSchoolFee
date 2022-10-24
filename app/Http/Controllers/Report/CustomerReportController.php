<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterRequest;
use App\Reports\CustomerReport;

class CustomerReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Customer Report');
        turnOffPreparedStatementEmulation();
        turnOffMysqlStictMode();
    }

    public function __invoke(FilterRequest $request)
    {
        abort_if(authUser()->cannot('Read Customer Report'), 403);

        $warehouses = authUser()->getAllowedWarehouses('transactions');

        $customerReport = new CustomerReport($request->validated());

        return view('reports.customer', compact('warehouses', 'customerReport'));
    }
}
