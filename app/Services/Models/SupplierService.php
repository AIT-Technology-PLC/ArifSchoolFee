<?php

namespace App\Services\Models;

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
}
