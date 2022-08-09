<?php

namespace App\Services\Models;

use Illuminate\Support\Facades\DB;

class CustomerService
{
    public function settleCredit($data, $customer)
    {
        $remainingAmount = $data['amount'];

        $credits = $customer->credits()->partiallySettled()->orWhere(function ($query) {
            $query->noSettlements();
        })->oldest()->get();

        return DB::transaction(function () use ($data, $credits, $remainingAmount) {
            foreach ($credits as $credit) {
                if ($remainingAmount == $credit->creditAmountUnsettled) {
                    $credit->creditSettlements()->create($data);
                    break;
                }
                if ($remainingAmount > $credit->creditAmountUnsettled) {
                    $data['amount'] = $credit->creditAmountUnsettled;
                    $remainingAmount -= $data['amount'];
                    $credit->creditSettlements()->create($data);
                    continue;
                }

                $data['amount'] = $remainingAmount;
                $remainingAmount = 0;
                $credit->creditSettlements()->create($data);
            }

            return [true, 'Amount settled successfully.'];
        });
    }
}
