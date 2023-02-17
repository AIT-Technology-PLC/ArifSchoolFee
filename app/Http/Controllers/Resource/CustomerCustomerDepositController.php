<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\CustomerDepositDatatable;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerDeposit;

class CustomerCustomerDepositController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Customer Management');

        $this->middleware('isFeatureAccessible:Customer Deposit Management');
    }

    public function index(Customer $customer, CustomerDepositDatatable $datatable)
    {
        $this->authorize('view', $customer);

        $this->authorize('viewAny', CustomerDeposit::class);

        $datatable->builder()->setTableId('customers-deposits-datatable')->orderBy(1, 'desc');

        $totalDeposits = $customer->customerDeposits()->count();

        $totalNotApproved = $customer->customerDeposits()->notApproved()->count();

        $totalApproved = $customer->customerDeposits()->approved()->count();

        $depositToDate = $customer->customerDeposits()->approved()->sum('amount');

        return $datatable->render('customers.customer-deposits.index', compact(
            'customer',
            'totalDeposits',
            'totalNotApproved',
            'totalApproved',
            'depositToDate')
        );
    }
}
