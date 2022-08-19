<?php

namespace App\Services\Models;

use Illuminate\Support\Facades\DB;

class SupplierService
{
    public function settleDebt($data, $supplier)
    {
        $remainingAmount = $data['amount'];

        $debts = $supplier->debts()->unsettled()->oldest()->get();

        return DB::transaction(function () use ($data, $debts, $remainingAmount) {
            foreach ($debts as $debt) {
                if ($remainingAmount == $debt->debtAmountUnsettled) {
                    $debt->debtSettlements()->create($data);
                    break;
                }
                if ($remainingAmount > $debt->debtAmountUnsettled) {
                    $data['amount'] = $debt->debtAmountUnsettled;
                    $remainingAmount -= $data['amount'];
                    $debt->debtSettlements()->create($data);
                    continue;
                }

                $data['amount'] = $remainingAmount;
                $remainingAmount = 0;
                $debt->debtSettlements()->create($data);
            }

            return [true, 'Amount settled successfully.'];
        });
    }
}
