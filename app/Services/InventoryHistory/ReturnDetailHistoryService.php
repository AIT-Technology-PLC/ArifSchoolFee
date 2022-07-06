<?php

namespace App\Services\InventoryHistory;

use App\Interfaces\DetailHistoryServiceInterface;
use App\Models\ReturnDetail;

class ReturnDetailHistoryService implements DetailHistoryServiceInterface
{
    private $warehouse, $product, $history;

    private function get()
    {
        $this->history = (new ReturnDetail())
            ->getByWarehouseAndProduct(
                $this->warehouse,
                $this->product
            );
    }

    private function format()
    {
        $this->history->transform(function ($returnDetail) {
            return [
                'type' => 'RETURN',
                'url' => '/returns/' . $returnDetail->return_id,
                'code' => $returnDetail->returnn->code,
                'date' => $returnDetail->returnn->issued_on,
                'quantity' => $returnDetail->quantity,
                'balance' => 0.00,
                'unit_of_measurement' => $this->product->unit_of_measurement,
                'details' => 'Returned to ' . $this->warehouse->name . ' from' . ($returnDetail->returnn->customer->company_name ?? ' Unknown'),
                'function' => 'add',
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