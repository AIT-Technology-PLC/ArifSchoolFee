<?php

namespace App\Services\InventoryHistory;

use App\Interfaces\DetailHistoryServiceInterface;
use App\Models\AdjustmentDetail;

class AdjustmentDetailHistoryService implements DetailHistoryServiceInterface
{
    private $warehouse;

    private $product;

    private $history;

    private function get()
    {
        $this->history = (new AdjustmentDetail())
            ->getByWarehouseAndProduct(
                $this->warehouse,
                $this->product
            );
    }

    private function format()
    {
        $this->history->transform(function ($adjustmentDetail) {
            return [
                'type' => 'ADJUSTMENT',
                'url' => '/adjustments/'.$adjustmentDetail->adjustment_id,
                'code' => $adjustmentDetail->adjustment->code,
                'date' => $adjustmentDetail->adjustment->issued_on,
                'quantity' => $adjustmentDetail->quantity,
                'balance' => 0.00,
                'unit_of_measurement' => $this->product->unit_of_measurement,
                'details' => ($adjustmentDetail->is_subtract ? 'Subtracted' : 'Added').' in '.$this->warehouse->name,
                'function' => $adjustmentDetail->is_subtract ? 'subtract' : 'add',
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
