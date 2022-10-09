<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerProfileFilterRequest;
use App\Models\Customer;
use App\Reports\SaleReport;

class CustomerProfileReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Customer Report');
        turnOffPreparedStatementEmulation();
        turnOffMysqlStictMode();
    }

    public function __invoke(CustomerProfileFilterRequest $request, Customer $customer)
    {
        abort_if(authUser()->cannot('Read Customer Profile Report'), 403);

        $warehouses = authUser()->getAllowedWarehouses('transactions');

        $saleReport = new SaleReport($request->validated());

        $totalCreditAmountProvided = $customer->credits()->sum('credit_amount');

        $currentCreditBalance = $totalCreditAmountProvided - $customer->credits()->sum('credit_amount_settled');

        $averageCreditSettlementDays = $customer->credits()->settled()->averageCreditSettlementDays();

        $currentCreditLimit = $customer->credit_amount_limit > 0 ? ($customer->credit_amount_limit - $currentCreditBalance) : $customer->credit_amount_limit;

        $lifetimeSalesReport = (new SaleReport($request->only('customer_id')));

        return view('reports.customer-profile', compact(
            'warehouses',
            'customer',
            'saleReport',
            'totalCreditAmountProvided',
            'currentCreditBalance',
            'averageCreditSettlementDays',
            'currentCreditLimit',
            'lifetimeSalesReport'
        ));
    }
}
