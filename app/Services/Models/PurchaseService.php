<?php

namespace App\Services\Models;

use App\Actions\ApproveTransactionAction;
use App\Notifications\PurchaseApproved;
use Illuminate\Support\Facades\DB;

class PurchaseService
{
    public function convertToGrn($purchase)
    {
        if (!$purchase->isPurchased()) {
            return [false, 'This purchase is not yet purchased.', ''];
        }

        if ($purchase->isClosed()) {
            return [false, 'This purchase is closed.', ''];
        }

        $data = [
            'purchase_id' => $purchase->id,
            'supplier_id' => $purchase->supplier_id,
            'grn' => $purchase->purchaseDetails->toArray(),
        ];

        return [true, '', $data];
    }

    public function convertToDebt($purchase)
    {
        if (!$purchase->isApproved()) {
            return [false, 'Creating a debt for purchase that is not approved is not allowed.'];
        }

        if ($purchase->debt()->exists()) {
            return [false, 'A debt for this purchase was already created.'];
        }

        if ($purchase->payment_type != 'Credit Payment') {
            return [false, 'Creating a debt for purchase with 0.00 debt amount is not allowed.'];
        }

        if (!$purchase->supplier()->exists()) {
            return [false, 'Creating a debt for purchase that has no supplier is not allowed.'];
        }

        if ($purchase->supplier->hasReachedDebtLimit($purchase->payment_in_debt)) {
            return [false, 'You exceed debt amount limit provided by this company.'];
        }

        $purchase->debt()->create([
            'supplier_id' => $purchase->supplier_id,
            'code' => nextReferenceNumber('debts'),
            'cash_amount' => $purchase->payment_in_cash,
            'debt_amount' => $purchase->payment_in_debt,
            'debt_amount_settled' => 0.00,
            'issued_on' => now(),
            'due_date' => $purchase->due_date,
        ]);

        return [true, ''];
    }

    public function approve($purchase)
    {
        return DB::transaction(function () use ($purchase) {
            [$isExecuted, $message] = (new ApproveTransactionAction)->execute($purchase, PurchaseApproved::class, 'Make Purchase');

            if (!$isExecuted) {
                DB::rollBack();
                return [$isExecuted, $message];
            }

            $this->convertToDebt($purchase);

            return [true, $message];
        });
    }
}
