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

    public function add($productId, $warehouseId, $quantity, $to)
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

    public function subtract($productId, $warehouseId, $quantity, $from)
    {
        $merchandise = $this->merchandise->where([
            ['product_id', $productId],
            ['warehouse_id', $warehouseId],
            [$from, '>=', $quantity],
        ])->first();

        $merchandise->$from = $merchandise->$from - $quantity;

        $merchandise->save();
    }
}
