<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterRequest;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Product;
use App\Reports\SaleReport;

class SaleByPaymentReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Sale By Payment Report');
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

        $customers = Customer::orderBy('company_name')->get();

        return view('reports.sale-by-payment', compact('saleReport', 'warehouses', 'users', 'products', 'customers'));
    }
}
