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

    public function add($product_id, $warehouse_id, $quantity, $to = 'available')
    {
        $merchandise = $this->merchandise->firstOrCreate(
            [
                'product_id' => $product_id,
                'warehouse_id' => $warehouse_id,
            ],
            [
                $to => 0.00,
            ]
        );

        $merchandise->$to = $merchandise->$to + $quantity;

        $merchandise->save();
    }

    public function isAvailable($product_id, $warehouse_id, $quantity, $in = 'available')
    {
        return $this->merchandise->where([
            ['product_id', $product_id],
            ['warehouse_id', $warehouse_id],
            [$in, '>=', $quantity],
        ])->exists();
    }

    public function subtract($product_id, $warehouse_id, $quantity, $from = 'available')
    {
        $merchandise = $this->merchandise->where([
            ['product_id', $product_id],
            ['warehouse_id', $warehouse_id],
            [$from, '>=', $quantity],
        ])->first();

        $merchandise->$from = $merchandise->$from - $quantity;

        $merchandise->save();
    }

    public function transfer($product_id, $transferred_to, $transferred_from, $quantity, $isSubtracted)
    {
        if ($isSubtracted) {
            $this->add($product_id, $transferred_to, $quantity);
        }

        if (!$isSubtracted) {
            $this->subtract($product_id, $transferred_from, $quantity);
        }
    }

    public function adjust($product_id, $warehouse_id, $quantity, $isSubtract)
    {
        if ($isSubtract) {
            $this->subtract($product_id, $warehouse_id, $quantity);
        }

        if (!$isSubtract) {
            $this->add($product_id, $warehouse_id, $quantity);
        }
    }

    public function reserve($product_id, $warehouse_id, $quantity)
    {
        $this->subtract($product_id, $warehouse_id, $quantity);

        $this->add($product_id, $warehouse_id, $quantity, 'reserved');
    }

    public function cancelReservation($product_id, $warehouse_id, $quantity)
    {
        $this->subtract($product_id, $warehouse_id, $quantity, 'reserved');

        $this->add($product_id, $warehouse_id, $quantity);
    }
}
