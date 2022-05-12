<?php

namespace App\Services\Models;

use App\Actions\ConvertToSivAction;
use App\Services\Inventory\InventoryOperationService;
use Illuminate\Support\Facades\DB;

class GdnService
{
    public function subtract($gdn, $user)
    {
        if (!$user->hasWarehousePermission('sales',
            $gdn->gdnDetails->pluck('warehouse_id')->toArray())) {
            return [false, 'You do not have permission to sell from one or more of the warehouses.'];
        }

        if (!$gdn->isApproved()) {
            return [false, 'This Delivery Order is not approved yet.'];
        }

        if ($gdn->isSubtracted()) {
            return [false, 'This Delivery Order is already subtracted from inventory'];
        }

        $from = $gdn->reservation()->exists() ? 'reserved' : 'available';

        $unavailableProducts = InventoryOperationService::unavailableProducts($gdn->gdnDetails, $from);

        if ($unavailableProducts->isNotEmpty()) {
            return [false, $unavailableProducts];
        }

        DB::transaction(function () use ($gdn, $from) {
            InventoryOperationService::subtract($gdn->gdnDetails, $from);

            $gdn->subtract();
        });

        return [true, ''];
    }

    public function convertToCredit($gdn)
    {
        if (!$gdn->isApproved()) {
            return [false, 'Creating a credit for delivery order that is not approved is not allowed.'];
        }

        if ($gdn->credit()->exists()) {
            return [false, 'A credit for this delivery order was already created.'];
        }

        if (($gdn->cash_received == 100 && $gdn->cash_received_type == 'percent') || ($gdn->cash_received == $this->grandTotalPriceAfterDiscount && $gdn->cash_received_type == 'amount') || $gdn->payment_in_credit == 0) {
            return [false, 'Creating a credit for delivery order with 0.00 credit amount is not allowed.'];
        }

        if (!$gdn->customer()->exists()) {
            return [false, 'Creating a credit for delivery order that has no customer is not allowed.'];
        }

        if ($gdn->customer->hasReachedCreditLimit($gdn->payment_in_credit)) {
            return [false, 'The customer has exceeded the credit amount limit.'];
        }

        $gdn->credit()->create([
            'customer_id' => $gdn->customer_id,
            'code' => nextReferenceNumber('credits'),
            'cash_amount' => $gdn->payment_in_cash,
            'credit_amount' => $gdn->payment_in_credit,
            'credit_amount_settled' => 0.00,
            'issued_on' => now(),
            'due_date' => $gdn->due_date,
        ]);

        return [true, ''];
    }

    public function convertToSiv($gdn, $user)
    {
        if (!$user->hasWarehousePermission('siv',
            $gdn->gdnDetails->pluck('warehouse_id')->toArray())) {
            return [false, 'You do not have permission to convert to one or more of the warehouses.', ''];
        }

        if (!$gdn->isSubtracted()) {
            return [false, 'This Delivery Order is not subtracted yet.', ''];
        }

        if ($gdn->isClosed()) {
            return [false, 'This Delivery Order is closed.', ''];
        }

        $siv = (new ConvertToSivAction)->execute(
            'DO',
            $gdn->code,
            $gdn->customer->company_name ?? '',
            $gdn->approved_by,
            $gdn->gdnDetails()->get(['product_id', 'warehouse_id', 'quantity']),
        );

        return [true, '', $siv];
    }

    public function close($gdn)
    {
        if (!$gdn->isSubtracted()) {
            return [false, 'This Delivery Order is not subtracted yet.'];
        }

        if ($gdn->isClosed()) {
            return [false, 'This Delivery Order is already closed.'];
        }

        $gdn->close();

        return [true, ''];
    }
}
