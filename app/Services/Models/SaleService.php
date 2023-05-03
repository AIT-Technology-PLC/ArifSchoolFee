<?php

namespace App\Services\Models;

use App\Actions\ApproveTransactionAction;
use App\Services\Integrations\PointOfSaleService;
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
        return DB::transaction(function () use ($sale) {
            $sale->credit()->forceDelete();

            $sale->cancel();

            [$isExecuted, $message] = $this->pointOfSaleService->cancel($sale);

            if ($sale->payment_type == 'Deposits' && $sale->gdns()->doesntExist() && $sale->isApproved()) {
                $sale->customer->incrementBalance($sale->grandTotalPriceAfterDiscount);
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
}
