<?php

namespace App\Services\Models;

use App\Models\Supplier;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SupplierService
{
    public function settleDebt($data, $supplier)
    {
        $settlementAmount = $data['amount'];

        $debts = $supplier->debts()->unsettled()->oldest()->get();

        return DB::transaction(function () use ($data, $debts, $settlementAmount) {
            foreach ($debts as $debt) {
                if ($settlementAmount <= $debt->debtAmountUnsettled) {
                    $data['amount'] = $settlementAmount;
                    $debt->debtSettlements()->create($data);
                    break;
                }

                if ($settlementAmount > $debt->debtAmountUnsettled) {
                    $data['amount'] = $debt->debtAmountUnsettled;
                    $settlementAmount -= $data['amount'];
                    $debt->debtSettlements()->create($data);
                    continue;
                }
            }

            return [true, 'Amount settled successfully.'];
        });
    }

    public function store($data)
    {
        return DB::transaction(function () use ($data) {
            $supplier = Supplier::firstOrCreate(
                Arr::only($data, 'company_name') + ['company_id' => userCompany()->id],
                Arr::except($data, 'company_name') + ['company_id' => userCompany()->id]
            );

            if (!empty($data['business_license_attachment'])) {
                $supplier->update([
                    'business_license_attachment' => $data['business_license_attachment']->store('supplier_business_licence', 'public'),
                ]);
            }

            return $supplier;
        });
    }

    public function update($supplier, $data)
    {
        return DB::transaction(function () use ($supplier, $data) {
            $supplier->update($data);

            if (!empty($data['business_license_attachment']) && is_object($data['business_license_attachment'])) {
                $supplier->update([
                    'business_license_attachment' => $data['business_license_attachment']->store('supplier_business_licence', 'public'),
                ]);
            }

            return $supplier;
        });
    }
}
