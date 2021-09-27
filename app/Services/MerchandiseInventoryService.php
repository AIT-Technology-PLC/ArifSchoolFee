<?php

namespace App\Services;

use App\Models\Merchandise;

class MerchandiseInventoryService
{
    private $merchandise;

    public function __construct()
    {
        $this->merchandise = new Merchandise();
    }

    public function add($productId, $warehouseId, $quantity, $to = 'available')
    {
        $merchandise = $this->merchandise->firstOrCreate(
            [
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
            ],
            [
                $to => 0.00,
            ]
        );

        $merchandise->$to = $merchandise->$to + $quantity;

        $merchandise->save();
    }

    public function isAvailable($productId, $warehouseId, $quantity, $in)
    {
        return $this->merchandise->where([
            ['product_id', $productId],
            ['warehouse_id', $warehouseId],
            [$in, '>=', $quantity],
        ])->exists();
    }

    public function subtract($productId, $warehouseId, $quantity, $from = 'available')
    {
        $merchandise = $this->merchandise->where([
            ['product_id', $productId],
            ['warehouse_id', $warehouseId],
            [$from, '>=', $quantity],
        ])->first();

        $merchandise->$from = $merchandise->$from - $quantity;

        $merchandise->save();
    }

    public function transfer($productId, $transferredTo, $transferredFrom, $quantity, $isSubtracted)
    {
        if ($isSubtracted) {
            $this->add($productId, $transferredTo, $quantity);
        }

        if (!$isSubtracted) {
            $this->subtract($productId, $transferredFrom, $quantity);
        }
    }

    public function adjust($productId, $warehouseId, $quantity, $isSubtract)
    {
        if ($isSubtract) {
            $this->subtract($productId, $warehouseId, $quantity);
        }

        if (!$isSubtract) {
            $this->add($productId, $warehouseId, $quantity);
        }
    }

    public function reserve($productId, $warehouseId, $quantity)
    {
        $this->subtract($productId, $warehouseId, $quantity);

        $this->add($productId, $warehouseId, $quantity, 'reserved');
    }

    public function cancelReservation($productId, $warehouseId, $quantity)
    {
        $this->subtract($productId, $warehouseId, $quantity, 'reserved');

        $this->add($productId, $warehouseId, $quantity);
    }
}
