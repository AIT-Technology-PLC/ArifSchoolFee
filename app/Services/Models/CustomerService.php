<?php

namespace App\Services\Models;

use App\Models\Customer;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CustomerService
{
    public function settleCredit($data, $customer)
    {
        $remainingAmount = $data['amount'];

        $credits = $customer->credits()->unsettled()->oldest()->get();

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

    public function store($data)
    {
        return DB::transaction(function () use ($data) {
            $customer = Customer::firstOrCreate(
                Arr::only($data, 'company_name') + ['company_id' => userCompany()->id],
                Arr::except($data, 'company_name') + ['company_id' => userCompany()->id]
            );

            if (!empty($data['business_license_attachment'])) {
                $customer->update([
                    'business_license_attachment' => $data['business_license_attachment']->store('customer_business_licence', 'public'),
                ]);
            }

            return $customer;
        });
    }
}
