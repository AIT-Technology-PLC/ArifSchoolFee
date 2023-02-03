<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\CustomerDeposit;
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
}