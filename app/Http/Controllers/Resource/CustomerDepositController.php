<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\CustomerDepositDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerDepositRequest;
use App\Http\Requests\UpdateCustomerDepositRequest;
use App\Models\Customer;
use App\Models\CustomerDeposit;
use App\Notifications\CustomerDepositCreated;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class CustomerDepositController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Customer Deposit Management');

        $this->authorizeResource(CustomerDeposit::class);
    }

    public function index(CustomerDepositDatatable $datatable)
    {
        $datatable->builder()->setTableId('customer-deposits-datatable')->orderBy(1, 'asc');

        $totalCustomerDeposits = CustomerDeposit::count();

        $totalAvailableBalance = Customer::sum('balance');

        $totalDeposits = CustomerDeposit::sum('amount');

        return $datatable->render('customer-deposits.index', compact('totalCustomerDeposits', 'totalAvailableBalance', 'totalDeposits'));
    }

    public function create()
    {
        return view('customer-deposits.create');
    }

    public function store(StoreCustomerDepositRequest $request)
    {
        $customerDeposits = collect($request->validated('customerDeposit'));

        DB::transaction(function () use ($customerDeposits) {
            foreach ($customerDeposits as $customerDeposit) {
                $deposit = CustomerDeposit::create($customerDeposit);

                if (isset($customerDeposit['attachment'])) {
                    $deposit->update([
                        'attachment' => $customerDeposit['attachment']->store('customer_deposits', 'public'),
                    ]);
                }

                Notification::send(Notifiables::byNextActionPermission('Approve Customer Deposit'), new CustomerDepositCreated($deposit));
            }
        });

        return redirect()->route('customer-deposits.index')->with('successMessage', 'New deposits are added.');
    }

    public function show(CustomerDeposit $customerDeposit)
    {
        return view('customer-deposits.show', compact('customerDeposit'));
    }

    public function edit(CustomerDeposit $customerDeposit)
    {
        if ($customerDeposit->isApproved()) {
            return back()->with('failedMessage', "You can't edit approved deposit");
        }

        return view('customer-deposits.edit', compact('customerDeposit'));
    }

    public function update(UpdateCustomerDepositRequest $request, CustomerDeposit $customerDeposit)
    {
        if ($customerDeposit->isApproved()) {
            return back()->with('failedMessage', "You can't update approved deposit");
        }

        $customerDeposit->update($request->validated());

        if ($request->hasFile('attachment')) {
            $customerDeposit->update([
                'attachment' => $customerDeposit->attachment->store('customer_deposits', 'public'),
            ]);
        }

        return redirect()->route('customer-deposits.show', $customerDeposit->id);
    }

    public function destroy(CustomerDeposit $customerDeposit)
    {
        if ($customerDeposit->isApproved() && !authUser()->can('Delete Approved Customer Deposit')) {
            return back()->with('failedMessage', "You can't delete approved deposit");
        }

        $customerDeposit->customer->decrement('balance', $customerDeposit->amount);

        $customerDeposit->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
