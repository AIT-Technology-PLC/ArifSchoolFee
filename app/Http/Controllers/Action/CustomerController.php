<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerCreditSettlementRequest;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\CustomerImport;
use App\Models\Customer;
use App\Services\Models\CustomerService;

class CustomerController extends Controller
{
    private $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->middleware('isFeatureAccessible:Customer Management');

        $this->customerService = $customerService;
    }

    public function import(UploadImportFileRequest $request)
    {
        $this->authorize('import', Customer::class);

        ini_set('max_execution_time', '-1');

        (new CustomerImport)->import($request->validated('file'));

        return back()->with('imported', __('messages.file_imported'));
    }

    public function settle(Customer $customer)
    {
        $this->authorize('settle', $customer->credits()->first());

        return view('customers.settle', compact('customer'));
    }

    public function settleCredit(StoreCustomerCreditSettlementRequest $request, Customer $customer)
    {
        $this->authorize('settle', $customer->credits()->first());

        [$isExecuted, $message] = $this->customerService->settleCredit($request->validated(), $customer);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return redirect()->route('customers.credits.index', $customer->id);
    }
}
