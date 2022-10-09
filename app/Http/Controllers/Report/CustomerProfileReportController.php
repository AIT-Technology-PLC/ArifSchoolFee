<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterRequest;
use App\Models\Customer;
use App\Reports\CustomerProfileReport;
use App\Reports\SaleReport;

class CustomerProfileReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Customer Report');
    }

    public function __invoke(FilterRequest $request, Customer $customer = null)
    {
        abort_if(authUser()->cannot('Read Customer Profile Report'), 403);

        $warehouses = authUser()->getAllowedWarehouses('transactions');

        $revenueReport = new SaleReport($request->validated('branches'), $request->validated('period'), $customer);

        $customerProfileReport = new CustomerProfileReport($request->validated('branches'), $request->validated('period'), $customer);

        return view('reports.profile', compact('warehouses', 'customer', 'customerProfileReport', 'revenueReport'));
    }
}