<?php

namespace App\Services\InventoryHistory;

use App\Interfaces\DetailHistoryServiceInterface;
use App\Models\DamageDetail;

class DamageDetailHistoryService implements DetailHistoryServiceInterface
{
    private $warehouse;

    private $product;

    private $history;

    private function get()
    {
        $this->history = (new DamageDetail())
            ->getByWarehouseAndProduct(
                $this->warehouse,
                $this->product
            );
    }

    private function format()
    {
        $this->history->transform(function ($damageDetail) {
            return [
                'type' => 'DAMAGE',
                'url' => '/damages/'.$damageDetail->damage_id,
                'code' => $damageDetail->damage->code,
                'date' => $damageDetail->damage->issued_on,
                'quantity' => $damageDetail->quantity,
                'balance' => 0.00,
                'unit_of_measurement' => $this->product->unit_of_measurement,
                'details' => 'Damaged in '.$this->warehouse->name,
                'function' => 'subtract',
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
