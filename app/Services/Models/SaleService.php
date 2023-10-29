<?php

namespace App\Services\Models;

use App\Actions\ApproveTransactionAction;
use App\Actions\ConvertToSivAction;
use App\Exceptions\InventoryHistoryDuplicateEntryException;
use App\Models\Sale;
use App\Notifications\SaleApproved;
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
            [$isExecuted, $message] = (new ApproveTransactionAction)->execute($sale, SaleApproved::class, 'Subtract Sale');

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

            if ($sale->company->canSaleSubtract() && $sale->isSubtracted()) {
                InventoryOperationService::add($sale->saleDetails, $sale);
                $sale->add();
                $sale->sivs()->forceDelete();
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

        DB::transaction(function () use ($sale) {
            $credit = $sale->credit()->create([
                'customer_id' => $sale->customer_id,
                'code' => nextReferenceNumber('credits'),
                'cash_amount' => $sale->payment_in_cash,
                'credit_amount' => $sale->payment_in_credit,
                'credit_amount_settled' => 0.00,
                'issued_on' => $sale->company->creditIssuedOnDate($sale),
                'due_date' => $sale->due_date,
            ]);

            $credit->storeConvertedCustomFields($sale, 'credit');
        });

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

        $from = $sale->reservation()->exists() ? 'reserved' : 'available';

        $unavailableProducts = InventoryOperationService::unavailableProducts($sale->saleDetails, $from);

        if ($unavailableProducts->isNotEmpty()) {
            return [false, $unavailableProducts];
        }

        DB::transaction(function () use ($sale, $from) {
            InventoryOperationService::subtract($sale->saleDetails, $sale, $from);

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

        $from = $sale->reservation()->exists() ? 'reserved' : 'available';

        $unavailableProducts = InventoryOperationService::unavailableProducts($sale->saleDetails, $from);

        if ($unavailableProducts->isNotEmpty()) {
            return [false, $unavailableProducts];
        }

        DB::transaction(function () use ($sale, $from) {
            (new ApproveTransactionAction)->execute($sale);

            if ($sale->payment_type == 'Deposits') {
                $sale->customer->decrementBalance($sale->grandTotalPriceAfterDiscount);
            }

            $this->convertToCredit($sale);

            InventoryOperationService::subtract($sale->saleDetails, $sale, $from);

            $sale->subtract();
        });

        return [true, ''];
    }

    public function assignFSNumber($data)
    {
        $sale = Sale::approved()
            ->notSubtracted()
            ->notCancelled()
            ->whereNull('fs_number')
            ->when(!auth()->check(), fn($q) => $q->where('warehouse_id', $data['warehouse_id']))
            ->where('code', $data['invoice_number'])
            ->first();

        if (!$sale) {
            return [false, 'Invoice not found and FS not assigned.'];
        }

        if (!$sale->warehouse->hasPosIntegration()) {
            return [false, 'Integration is not set up for this branch.'];
        }

        $from = $sale->reservation()->exists() ? 'reserved' : 'available';

        $unavailableProducts = InventoryOperationService::unavailableProducts($sale->saleDetails, $from);

        if ($unavailableProducts->isNotEmpty()) {
            return [false, $unavailableProducts];
        }

        DB::transaction(function () use ($sale, $data, $from) {
            try {
                InventoryOperationService::subtract($sale->saleDetails, $sale, $from);

                $sale->assignFSNumber($data['fs_number']);

                $sale->subtract();
            } catch (InventoryHistoryDuplicateEntryException $ex) {
                DB::rollBack();
            }
        }, 2);

        return [true, 'Invoice found and FS assigned successfully.'];
    }

    public function convertToSiv($sale, $user, $sivDetails)
    {
        if (!$user->hasWarehousePermission('siv',
            $sale->saleDetails->pluck('warehouse_id')->toArray())) {
            return [false, 'You do not have permission to convert to one or more of the warehouses.', ''];
        }

        if ($sale->sivs()->exists() && !userCompany()->isPartialDeliveriesEnabled()) {
            return [false, 'Siv for this invoice was already created.'];
        }

        if ($sale->isCancelled()) {
            return [false, 'This Invoice is cancelled.', ''];
        }

        if ($sale->isFullyDelivered()) {
            return [false, 'This Invoice is fully delivered.', ''];
        }

        if (!$sale->isSubtracted()) {
            return [false, 'This Invoice is not subtracted yet.', ''];
        }

        $siv = DB::transaction(function () use ($sale, $sivDetails) {
            $siv = (new ConvertToSivAction)->execute(
                $sale,
                $sale->customer->company_name ?? '',
                userCompany()->isPartialDeliveriesEnabled() ? collect($sivDetails) : $sale->saleDetails()->get(['product_id', 'warehouse_id', 'merchandise_batch_id', 'quantity']),
            );

            $siv->storeConvertedCustomFields($sale, 'siv');

            return $siv;
        });

        return [true, '', $siv];
    }
}
