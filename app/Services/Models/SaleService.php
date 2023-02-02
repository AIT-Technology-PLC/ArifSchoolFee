<?php

namespace App\Services\Models;

use App\Actions\ApproveTransactionAction;
use App\Models\Gdn;
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

            if ($sale->payment_type == 'Customer Deposit' && Gdn::where('sale_id', $sale->id)->doesntExist()) {
                $sale->customer->balance = $sale->customer->balance - $sale->grandTotalPriceAfterDiscount;

                $sale->customer->save();
            }

            if (!$isExecuted) {
                DB::rollBack();
                return [$isExecuted, $message];
            }

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
            $sale->cancel();

            [$isExecuted, $message] = $this->pointOfSaleService->cancel($sale);

            if ($sale->payment_type == 'Customer Deposit' && Gdn::where('sale_id', $sale->id)->doesntExist() && $sale->isApproved()) {
                $sale->customer->balance = $sale->customer->balance + $sale->grandTotalPriceAfterDiscount;

                $sale->customer->save();
            }

            if (!$isExecuted) {
                DB::rollBack();
                return [$isExecuted, $message];
            }

            return [true, 'Invoice cancelled successfully'];
        });
    }
}