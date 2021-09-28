<?php

namespace App\Services\InventoryHistory;

use App\Models\Product;
use App\Models\Warehouse;

class ProductPerWarehouseHistoryService
{
    private $histories = [
        GrnDetailHistoryService::class,
        GdnDetailHistoryService::class,
        TransferDetailHistoryService::class,
        DamageDetailHistoryService::class,
        ReturnDetailHistoryService::class,
        AdjustmentDetailHistoryService::class,
        ReservationDetailHistoryService::class,
    ];

    private $warehouse, $product, $history;

    public function __construct()
    {
        $this->history = collect();
    }

    public function get(Warehouse $warehouse, Product $product)
    {
        $this->warehouse = $warehouse;

        $this->product = $product;

        return $this
            ->merge()
            ->sort()
            ->calculate()
            ->history;
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

        return $this;
    }

    private function sort()
    {
        $this->history = collect($this->history->sortBy('date')->values()->all());

        return $this;
    }

    private function calculate()
    {
        for ($i = 0; $i < $this->history->count(); $i++) {
            $method = $this->history[$i]['function'];

            if ($i == 0) {
                $this->history[$i] = $this->$method($this->history[$i]);
                continue;
            }

            $this->history[$i] = $this->$method($this->history[$i], $this->history[$i - 1]['balance']);
        }

        return $this;
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
