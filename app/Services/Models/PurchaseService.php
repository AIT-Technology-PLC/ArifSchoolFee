<?php

namespace App\Services\Models;

use App\Actions\ApproveTransactionAction;
use App\Notifications\PurchaseApproved;
use App\Notifications\PurchaseCancelled;
use App\Notifications\PurchaseMade;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

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

        $purchaseDetails = $purchase
            ->purchaseDetails
            ->map(function ($item) {
                $item['unit_cost'] = round($item->unitCostAfterTax, 2);

                return $item;
            });

        $data = [
            'purchase_id' => $purchase->id,
            'supplier_id' => $purchase->supplier_id,
            'grn' => $purchaseDetails,
        ];

        return [true, '', $data];
    }

    public function convertToDebt($purchase)
    {
        if (!$purchase->isApproved()) {
            return [false, 'Creating a debt for purchase that is not approved is not allowed.'];
        }

        if ($purchase->isCancelled()) {
            return [false, 'This purchase is already cancelled.'];
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
        if ($purchase->isRejected()) {
            return [false, 'You can not approve a purchase that is rejected.'];
        }

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

    public function cancel($purchase)
    {
        if ($purchase->isPurchased()) {
            return [false, 'You can not cancel a purchase that is already purchased.'];
        }

        if (!$purchase->isApproved()) {
            return [false, 'This Purchase is not approved yet.'];
        }

        if ($purchase->isCancelled()) {
            return [false, 'This Purchase is already cancelled'];
        }

        DB::transaction(function () use ($purchase) {
            $purchase->debt()->forceDelete();

            $purchase->cancel();

            Notification::send(
                Notifiables::byPermissionAndWarehouse('Read Purchase', $purchase->warehouse_id, $purchase->createdBy),
                new PurchaseCancelled($purchase)
            );
        });

        return [true, ''];
    }

    public function purchase($purchase)
    {
        if (!$purchase->isApproved()) {
            return back()->with('failedMessage', 'This purchase is not yet approved.');
        }

        if ($purchase->isCancelled()) {
            return back()->with('failedMessage', 'You can not purchased a cancelled purchase.');
        }

        if ($purchase->isPurchased()) {
            return back()->with('failedMessage', 'This purchase is already purchased.');
        }

        DB::transaction(function () use ($purchase) {
            $purchase->purchase();

            Notification::send(
                Notifiables::byPermissionAndWarehouse('Read Purchase', $purchase->warehouse_id, $purchase->createdBy),
                new PurchaseMade($purchase)
            );
        });

        return [true, ''];
    }

    public function approveAndPurchase($purchase)
    {
        if ($purchase->isApproved()) {
            return back()->with('failedMessage', 'This purchase is already approved.');
        }

        if ($purchase->isCancelled()) {
            return back()->with('failedMessage', 'You can not purchased a cancelled purchase.');
        }

        if ($purchase->isPurchased()) {
            return back()->with('failedMessage', 'This purchase is already purchased.');
        }

        DB::transaction(function () use ($purchase) {
            (new ApproveTransactionAction)->execute($purchase);

            $purchase->purchase();

            Notification::send(
                Notifiables::byPermissionAndWarehouse('Read Purchase', $purchase->warehouse_id, $purchase->createdBy),
                new PurchaseMade($purchase)
            );
        });

        return [true, ''];
    }
}