<?php

namespace App\Services\Models;

use App\Actions\ApproveTransactionAction;
use App\Models\Customer;
use App\Notifications\CustomerDepositApproved;
use Illuminate\Support\Facades\DB;

class CustomerDepositService
{
    public function approve($customerDeposit)
    {
        return DB::transaction(function () use ($customerDeposit) {
            [$isExecuted, $message] = (new ApproveTransactionAction)->execute($customerDeposit, CustomerDepositApproved::class, 'Read Customer Deposit');

            $customer = Customer::where('id', $customerDeposit->customer_id)->first();

            $customer->incrementBalance($customerDeposit->amount);

            if (!$isExecuted) {
                DB::rollBack();
                return [$isExecuted, $message];
            }

            return [true, $message];
        });
    }
}