<?php

namespace App\Http\Controllers\Action;

use App\Models\Customer;
use App\Models\CustomerDeposit;
use App\Http\Controllers\Controller;
use App\DataTables\CustomerDepositDatatable;
use App\Services\Models\CustomerDepositService;

class CustomerDepositController extends Controller
{
    private $customerDepositService;

    public function __construct(CustomerDepositService $customerDepositService)
    {
        $this->middleware('isFeatureAccessible:Customer Deposit Management');

        $this->customerDepositService = $customerDepositService;
    }

    public function approve(CustomerDeposit $customerDeposit)
    {
        $this->authorize('approve', $customerDeposit);

        [$isExecuted, $message] = $this->customerDepositService->approve($customerDeposit);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function deposit(Customer $customer, CustomerDepositDatatable $datatable)
    {
        $this->authorize('view', $customer);

        $this->authorize('viewAny', CustomerDeposit::class);

        $datatable->builder()->setTableId('customers-deposits-datatable')->orderBy(1, 'desc');

        $totalNumberOfDeposits = $customer->customerDeposits()->count();

        $totalDeposits = $customer->customerDeposits()->sum('amount');

        return $datatable->render('customer-deposits.deposit', compact(
            'customer',
            'totalNumberOfDeposits',
            'totalDeposits'
        )
        );
    }
}