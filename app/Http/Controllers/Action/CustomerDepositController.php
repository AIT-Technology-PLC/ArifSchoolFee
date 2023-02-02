<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\DataTables\CustomerDepositDatatable;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerDeposit;
use App\Notifications\CustomerDepositApproved;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\Notification;

class CustomerDepositController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Customer Deposit Management');

    }

    public function approve(CustomerDeposit $customerDeposit, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $customerDeposit);

        [$isExecuted, $message] = $action->execute($customerDeposit);

        $customer = Customer::where('id', $customerDeposit->customer_id)->first();

        $customer->balance = $customer->balance + $customerDeposit->amount;

        $customer->save();

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermission('Read Customer Deposit', $customerDeposit->createdBy),
            new CustomerDepositApproved($customerDeposit)
        );

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