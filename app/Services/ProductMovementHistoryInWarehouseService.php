<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Warehouse;
use App\Services\InventoryHistory as InventoryHistory;

class ProductMovementHistoryInWarehouseService
{
    private $histories = [
        InventoryHistory\GrnDetailHistoryService::class,
        InventoryHistory\GdnDetailHistoryService::class,
        InventoryHistory\TransferDetailHistoryService::class,
        InventoryHistory\DamageDetailHistoryService::class,
        InventoryHistory\ReturnDetailHistoryService::class,
        InventoryHistory\AdjustmentDetailHistoryService::class,
        InventoryHistory\ReservationDetailHistoryService::class,
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
            $historyClass::formatted($this->warehouse, $this->product)
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
