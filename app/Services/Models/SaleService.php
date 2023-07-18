<?php

namespace App\Services\Models;

use App\Actions\ApproveTransactionAction;
use App\Services\Integrations\PointOfSaleService;
use App\Services\Inventory\InventoryOperationService;
use Illuminate\Support\Facades\DB;

class SaleService
{
    private $pointOfSaleService;

    public function __construct(PointOfSaleService $pointOfSaleService)
    {
        $this->pointOfSaleService = $pointOfSaleService;
    }

    public function approve($sale)
    {
        return DB::transaction(function () use ($sale) {
            [$isExecuted, $message] = (new ApproveTransactionAction)->execute($sale);

            if ($sale->payment_type == 'Deposits' && $sale->gdns()->doesntExist()) {
                $sale->customer->decrementBalance($sale->grandTotalPriceAfterDiscount);
            }

            if (!$isExecuted) {
                DB::rollBack();
                return [$isExecuted, $message];
            }

            $this->convertToCredit($sale);

            [$isExecuted, $message] = $this->pointOfSaleService->create($sale);

            if (!$isExecuted) {
                DB::rollBack();
                return [$isExecuted, $message];
            }

            return [true, 'Invoice approved successfully'];
        });
    }

    public function cancel($sale)
    {
        if ($sale->isCancelled()) {
            return [false, 'This Invoice is already cancelled'];
        }

        return DB::transaction(function () use ($sale) {
            $sale->credit()->forceDelete();

            $sale->cancel();

            [$isExecuted, $message] = $this->pointOfSaleService->cancel($sale);

            if ($sale->payment_type == 'Deposits' && $sale->gdns()->doesntExist() && $sale->isApproved()) {
                $sale->customer->incrementBalance($sale->grandTotalPriceAfterDiscount);
            }

            if (userCompany()->canSaleSubtract() && $sale->isSubtracted()) {
                InventoryOperationService::add($sale->gdnDetails, $sale);
                $sale->add();
                $sale->sale?->cancel();
                Siv::where('purpose', 'DO')->where('ref_num', $sale->code)->forceDelete();
            }

            if (!$isExecuted) {
                DB::rollBack();
                return [$isExecuted, $message];
            }

            return [true, 'Invoice cancelled successfully'];
        });
    }

    public function convertToCredit($sale)
    {
        if (!$sale->isApproved()) {
            return [false, 'Creating a credit for invoice that is not approved is not allowed.'];
        }

        if ($sale->isCancelled()) {
            return [false, 'This invoice is cancelled.'];
        }

        if ($sale->credit()->exists()) {
            return [false, 'A credit for this invoice was already created.'];
        }

        if ($sale->payment_type != 'Credit Payment' || $sale->grand_total_price == 0) {
            return [false, 'Creating a credit for invoice with 0.00 credit amount is not allowed.'];
        }

        if (!$sale->customer()->exists()) {
            return [false, 'Creating a credit for invoice that has no customer is not allowed.'];
        }

        if ($sale->customer->hasReachedCreditLimit($sale->payment_in_credit)) {
            return [false, 'The customer has exceeded the credit amount limit.'];
        }

        $sale->credit()->create([
            'customer_id' => $sale->customer_id,
            'code' => nextReferenceNumber('credits'),
            'cash_amount' => $sale->payment_in_cash,
            'credit_amount' => $sale->payment_in_credit,
            'credit_amount_settled' => 0.00,
            'issued_on' => now(),
            'due_date' => $sale->due_date,
        ]);

        return [true, ''];
    }

    public function subtract($sale, $user)
    {
        if (!userCompany()->canSaleSubtract()) {
            return [false, 'Subtracting invoice is not allow. Contact your System Manager.'];
        }

        if (!$user->hasWarehousePermission('sales',
            $sale->saleDetails->pluck('warehouse_id')->toArray())) {
            return [false, 'You do not have permission to sell from one or more of the warehouses.'];
        }

        if (!$sale->isApproved()) {
            return [false, 'This Invoice is not approved yet.'];
        }

        if ($sale->isCancelled()) {
            return [false, 'This Invoice is cancelled.'];
        }

        if ($sale->isSubtracted()) {
            return [false, 'This Invoice is already subtracted from inventory'];
        }

        $unavailableProducts = InventoryOperationService::unavailableProducts($sale->saleDetails);

        if ($unavailableProducts->isNotEmpty()) {
            return [false, $unavailableProducts];
        }

        DB::transaction(function () use ($sale) {
            InventoryOperationService::subtract($sale->saleDetails, $sale);

            $sale->subtract();
        });

        return [true, ''];
    }

    public function approveAndSubtract($sale, $user)
    {
        if (!userCompany()->canSaleSubtract()) {
            return [false, 'Subtracting invoice is not allow. Contact your System Manager.'];
        }

        if (!$user->hasWarehousePermission('sales',
            $sale->saleDetails->pluck('warehouse_id')->toArray())) {
            return [false, 'You do not have permission to sell from one or more of the warehouses.'];
        }

        if ($sale->isApproved()) {
            return [false, 'This Delivery Order is already approved.'];
        }

        if ($sale->isCancelled()) {
            return [false, 'This Delivery Order is cancelled.'];
        }

        if ($sale->isSubtracted()) {
            return [false, 'This Delivery Order is already subtracted from inventory'];
        }

        $unavailableProducts = InventoryOperationService::unavailableProducts($sale->saleDetails);

        if ($unavailableProducts->isNotEmpty()) {
            return [false, $unavailableProducts];
        }

        DB::transaction(function () use ($sale) {
            (new ApproveTransactionAction)->execute($sale);

            if ($sale->payment_type == 'Deposits') {
                $sale->customer->decrementBalance($sale->grandTotalPriceAfterDiscount);
            }

            $this->convertToCredit($sale);

            InventoryOperationService::subtract($sale->saleDetails, $sale);

            $sale->subtract();
        });

        return [true, ''];
    }
}
