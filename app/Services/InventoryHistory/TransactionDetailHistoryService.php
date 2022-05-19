<?php

namespace App\Services\InventoryHistory;

use App\Interfaces\DetailHistoryServiceInterface;
use App\Models\TransactionField;

class TransactionDetailHistoryService implements DetailHistoryServiceInterface
{
    private $warehouse, $product, $history;

    private function get()
    {
        $this->history = (new TransactionField())
            ->getByWarehouseAndProduct(
                $this->warehouse,
                $this->product
            );
    }

    private function format()
    {
        $this->history->transform(function ($transactionDetail) {
            return [
                'type' => $transactionDetail['transaction']->pad->name,
                'code' => $transactionDetail['transaction']->code,
                'date' => $transactionDetail['transaction']->issued_on,
                'quantity' => number_format($transactionDetail['quantity'], 2, thousands_separator:''),
                'balance' => 0.00,
                'unit_of_measurement' => $this->product->unit_of_measurement,
                'details' => str($transactionDetail['transaction']->pad->inventory_operation_type)->append('ed', ' in ', $this->warehouse->name)->ucfirst(),
                'function' => $transactionDetail['transaction']->pad->inventory_operation_type,
            ];
        });
    }

    public function retrieve($warehouse, $product)
    {
        $this->product = $product;

        $this->warehouse = $warehouse;

        $this->get();

        $this->format();

        return $this->history;
    }
}
