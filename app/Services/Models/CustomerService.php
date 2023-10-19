<?php

namespace App\Services\Models;

use App\Models\Customer;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CustomerService
{
    public function settleCredit($data, $customer)
    {
        $settlementAmount = $data['amount'];

        $credits = $customer->credits()->whereDate('issued_on', '<=', $data['settled_at'])->unsettled()->oldest()->get();

        return DB::transaction(function () use ($data, $credits, $settlementAmount) {
            foreach ($credits as $credit) {
                if ($settlementAmount <= $credit->creditAmountUnsettled) {
                    $data['amount'] = $settlementAmount;
                    $credit->creditSettlements()->create($data);
                    break;
                }

                if ($settlementAmount > $credit->creditAmountUnsettled) {
                    $data['amount'] = $credit->creditAmountUnsettled;
                    $settlementAmount -= $data['amount'];
                    $credit->creditSettlements()->create($data);
                    continue;
                }
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

    public function update($customer, $data)
    {
        return DB::transaction(function () use ($customer, $data) {
            $customer->update($data);

            if (!empty($data['business_license_attachment']) && is_object($data['business_license_attachment'])) {
                $customer->update([
                    'business_license_attachment' => $data['business_license_attachment']->store('customer_business_licence', 'public'),
                ]);
            }

            return $customer;
        });
    }
}
