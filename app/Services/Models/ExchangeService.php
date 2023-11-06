<?php

namespace App\Services\Models;

use App\Actions\ApproveTransactionAction;
use App\Models\Gdn;
use App\Models\Returnn;
use App\Models\Sale;
use App\Notifications\ExchangeApproved;
use App\Services\Integrations\PointOfSaleService;
use App\Services\Models\GdnService;
use App\Services\Models\ReturnService;
use App\Services\Models\SaleService;
use Illuminate\Support\Facades\DB;

class ExchangeService
{
    public function approve($exchange)
    {
        return DB::transaction(function () use ($exchange) {
            [$isExecuted, $message] = (new ApproveTransactionAction)->execute($exchange, ExchangeApproved::class, 'Execute Exchange');

            if (!$isExecuted) {
                DB::rollBack();
                return [$isExecuted, $message];
            }

            return [true, $message];
        });
    }

    public function execute($exchange, $user)
    {
        if (!$user->hasWarehousePermission('sales', $exchange->exchangeDetails->pluck('warehouse_id')->toArray())) {
            return [false, 'You do not have permission to sell from one or more of the warehouses.'];
        }

        if (!$exchange->isApproved()) {
            return [false, 'This Exchange is not approved yet.'];
        }

        if ($exchange->isExecuted()) {
            return [false, 'This Exchange is already executed'];
        }

        DB::transaction(function () use ($exchange) {
            $exchangeable = $exchange->exchangeable;

            $this->executeReturn($exchange);

            if ($exchangeable instanceof Gdn) {
                $this->executeGdn($exchange);

            } elseif ($exchangeable instanceof Sale) {
                $this->executeSale($exchange);
            }

            $exchange->executed();
        });

        return [true, ''];
    }

    private function executeGdn($exchange)
    {
        $gdn = Gdn::create([
            'customer_id' => $exchange->exchangeable->customer_id ?? null,
            'contact_id' => $exchange->exchangeable->contact_id ?? null,
            'code' => nextReferenceNumber('gdns'),
            'payment_type' => $exchange->exchangeable->payment_type,
            'cash_received_type' => $exchange->exchangeable->cash_received_type,
            'cash_received' => $exchange->exchangeable->cash_received,
            'description' => $exchange->exchangeable->description ?? '',
            'issued_on' => now(),
            'due_date' => $exchange->exchangeable->due_date,
        ]);

        $exchange->exchangeable()->associate($gdn)->save();

        $exchange->exchangeDetails->each(function ($exchangeDetail) use ($gdn) {
            $gdnDetail = $gdn->gdnDetails()->create($exchangeDetail->toArray());

            $exchangeDetail->exchangeDetailable()->associate($gdnDetail)->save();
        });

        $gdnService = new GdnService();

        $gdnService->approveAndSubtract($gdn, authUser());

        $gdn->storeConvertedCustomFields($exchange, 'gdn');
    }

    private function executeSale($exchange)
    {
        $sale = Sale::create([
            'customer_id' => $exchange->exchangeable->customer_id ?? null,
            'contact_id' => $exchange->exchangeable->contact_id ?? null,
            'code' => nextReferenceNumber('sales'),
            'payment_type' => $exchange->exchangeable->payment_type,
            'cash_received_type' => $exchange->exchangeable->cash_received_type,
            'cash_received' => $exchange->exchangeable->cash_received,
            'description' => $exchange->exchangeable->description ?? '',
            'issued_on' => now(),
            'due_date' => $exchange->exchangeable->due_date ?? null,
        ]);

        $exchange->exchangeable()->associate($sale)->save();

        $exchange->exchangeDetails->each(function ($exchangeDetail) use ($sale) {
            $saleDetail = $sale->saleDetails()->create($exchangeDetail->toArray());

            $exchangeDetail->exchangeDetailable()->associate($saleDetail)->save();
        });

        $saleService = new SaleService(new PointOfSaleService);

        $saleService->approveAndSubtract($sale, authUser());

        $sale->storeConvertedCustomFields($exchange, 'sale');
    }

    private function executeReturn($exchange)
    {
        $return = Returnn::create([
            'customer_id' => $exchange->exchangeable->customer_id ?? null,
            'code' => nextReferenceNumber('returns'),
            'gdn_id' => $exchange->exchangeable instanceof Gdn ? $exchange->exchangeable->id : null,
            'description' => $exchange->exchangeable->description ?? '',
            'issued_on' => now(),
            'due_date' => $exchange->exchangeable->due_date ?? null,
        ]);

        $exchange->return_id = $return->id;

        $exchange->save();

        $details = collect($exchange->exchangeDetail)
            ->map(function ($item) {
                $item['quantity'] = $item['returned_quantity'];

                return $item;
            });

        $return->returnDetails()->createMany($details->toArray());

        $returnService = new ReturnService();

        $returnService->approveAndAdd($return, authUser());

        $return->storeConvertedCustomFields($exchange, 'return');
    }

    public function approveAndExecute($exchange, $user)
    {
        if (!$user->hasWarehousePermission('sales', $exchange->exchangeDetails->pluck('warehouse_id')->toArray())) {
            return [false, 'You do not have permission to sell from one or more of the warehouses.'];
        }

        if ($exchange->isApproved()) {
            return [false, 'This Exchange is already approved yet.'];
        }

        if ($exchange->isExecuted()) {
            return [false, 'This Exchange is already executed'];
        }

        DB::transaction(function () use ($exchange) {
            (new ApproveTransactionAction)->execute($exchange);

            $exchangeable = $exchange->exchangeable;

            $this->executeReturn($exchange);

            if ($exchangeable instanceof Gdn) {
                $this->executeGdn($exchange);

            } elseif ($exchangeable instanceof Sale) {
                $this->executeSale($exchange);
            }

            $exchange->executed();
        });

        return [true, ''];
    }
}
