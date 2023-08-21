<?php

namespace App\Services\Models;

use App\Actions\ApproveTransactionAction;
use App\Actions\ConvertToSivAction;
use App\Models\Sale;
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

            if ($sale->payment_type == 'Deposits' && $sale->gdns()->doesntExist() && $sale->isApproved()) {
                $sale->customer->incrementBalance($sale->grandTotalPriceAfterDiscount);
            }

            if (userCompany()->canSaleSubtract() && $sale->isSubtracted()) {
                InventoryOperationService::add($sale->gdnDetails, $sale);
                $sale->add();
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
        if ($sale->warehouse->hasPosIntegration()) {
            return [false, 'Subtracting is not allowed.'];
        }

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
        if ($sale->warehouse->hasPosIntegration()) {
            return [false, 'Subtracting is not allowed.'];
        }

        if (!userCompany()->canSaleSubtract()) {
            return [false, 'Subtracting invoice is not allow. Contact your System Manager.'];
        }

        if (!$user->hasWarehousePermission('sales',
            $sale->saleDetails->pluck('warehouse_id')->toArray())) {
            return [false, 'You do not have permission to sell from one or more of the warehouses.'];
        }

        if ($sale->isApproved()) {
            return [false, 'This Invoice is already approved.'];
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

    public function assignFSNumber($data)
    {
        $sale = Sale::approved()->notSubtracted()->notCancelled()->where('code', $data['invoice_number'])->whereNull('fs_number')->first();

        if (!$sale) {
            return [false, 'Invoice not found and FS not assigned.'];
        }

        if (!$sale->warehouse->hasPosIntegration()) {
            return [false, 'Integration is not set up for this branch.'];
        }

        $unavailableProducts = InventoryOperationService::unavailableProducts($sale->saleDetails);

        if ($unavailableProducts->isNotEmpty()) {
            return [false, $unavailableProducts];
        }

        DB::transaction(function () use ($sale, $data) {
            InventoryOperationService::subtract($sale->saleDetails, $sale);

            $sale->assignFSNumber($data['fs_number']);

            $sale->subtract();
        });

        return [true, 'Invoice found and FS assigned successfully.'];
    }

    public function convertToSiv($sale, $user)
    {
        if (!$user->hasWarehousePermission('siv',
            $sale->saleDetails->pluck('warehouse_id')->toArray())) {
            return [false, 'You do not have permission to convert to one or more of the warehouses.', ''];
        }

        if ($sale->isCancelled()) {
            return [false, 'This Invoice is cancelled.', ''];
        }

        if (!$sale->isSubtracted()) {
            return [false, 'This Invoice is not subtracted yet.', ''];
        }

        $siv = (new ConvertToSivAction)->execute(
            'Invoice',
            $sale->code,
            $sale->customer->company_name ?? '',
            $sale->approved_by,
            $sale->saleDetails()->get(['product_id', 'warehouse_id', 'quantity']),
        );

        return [true, '', $siv];
    }
}
