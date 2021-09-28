<?php

namespace App\Services\InventoryHistory;

use App\Interfaces\DetailHistoryServiceInterface;
use App\Models\AdjustmentDetail;

class AdjustmentDetailHistoryService implements DetailHistoryServiceInterface
{
    private $warehouse, $product, $history;

    private function get()
    {
        $this->history = (new AdjustmentDetail())
            ->getByWarehouseAndProduct(
                $this->warehouse,
                $this->product
            );

        return $this;
    }

    private function format()
    {
        $this->history->transform(function ($adjustmentDetail) {
            return [
                'type' => 'ADJUSTMENT',
                'code' => $adjustmentDetail->adjustment->code,
                'date' => $adjustmentDetail->adjustment->issued_on,
                'quantity' => $adjustmentDetail->quantity,
                'balance' => 0.00,
                'unit_of_measurement' => $this->product->unit_of_measurement,
                'details' => ($adjustmentDetail->is_subtract ? 'Subtracted' : 'Added') . ' in ' . $this->warehouse->name,
                'function' => $adjustmentDetail->is_subtract ? 'subtract' : 'add',
            ];
        });

        return $this;
    }

    public function retrieve($warehouse, $product)
    {
        $this->product = $product;

        $this->warehouse = $warehouse;

        return $this->get()->format()->history;
    }
}
