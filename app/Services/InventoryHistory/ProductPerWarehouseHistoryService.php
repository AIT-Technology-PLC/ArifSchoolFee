<?php

namespace App\Services\InventoryHistory;

use App\Models\Product;
use App\Models\Warehouse;

class ProductPerWarehouseHistoryService
{
    private $histories = [
        GrnDetailHistoryService::class,
        GdnDetailHistoryService::class,
        SaleDetailHistoryService::class,
        TransferDetailHistoryService::class,
        DamageDetailHistoryService::class,
        ReturnDetailHistoryService::class,
        AdjustmentDetailHistoryService::class,
        ReservationDetailHistoryService::class,
        TransactionDetailHistoryService::class,
        JobDetailHistoryService::class,
        JobExtraHistoryService::class,
        BundleDetailHistoryService::class,
        SivDetailHistoryService::class,
    ];

    private $warehouse;

    private $product;

    private $history;

    public function __construct()
    {
        $this->history = collect();
    }

    public function get(Warehouse $warehouse, Product $product)
    {
        $this->warehouse = $warehouse;

        $this->product = $product;

        $this->merge();

        $this->sort();

        $this->calculate();

        return $this->history;
    }

    private function merge()
    {
        foreach ($this->histories as $historyClass) {
            (new $historyClass)
                ->retrieve($this->warehouse, $this->product)
                ->each(function ($item) {
                    $this->history->push($item);
                });
        }
    }

    private function sort()
    {
        $this->history = collect($this->history->sortBy('date')->values()->all());
    }

    private function calculate()
    {
        $this->history
            ->each(function ($item, $key) {
                $method = $this->history[$key]['function'];

                if ($key == 0) {
                    $this->history[$key] = $this->$method($this->history[$key]);

                    return;
                }

                $this->history[$key] = $this->$method($this->history[$key], $this->history[$key - 1]['balance']);
            });
    }

    private function subtract($item, $previousBalance = 0)
    {
        $item['balance'] = $previousBalance - $item['quantity'];

        return $item;
    }

    private function add($item, $previousBalance = 0)
    {
        $item['balance'] = $previousBalance + $item['quantity'];

        return $item;
    }
}
