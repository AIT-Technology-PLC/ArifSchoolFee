<?php

namespace App\Services\Inventory;

use App\Models\Merchandise;

class InventoryOperationService
{
    private $merchandise;

    public function __construct()
    {
        $this->merchandise = new Merchandise();
    }
    public static function add($details, $to = 'available')
    {
        foreach ($details as $detail) {
            $merchandise = Merchandise::firstOrCreate(
                [
                    'product_id' => $detail['product_id'],
                    'warehouse_id' => $detail['warehouse_id'],
                ],
                [
                    $to => 0.00,
                ]
            );

            $merchandise->$to = $merchandise->$to + $detail['quantity'];

            $merchandise->save();
        }
    }

    public static function subtract($details, $from = 'available')
    {
        $merchandises = Merchandise::all();

        foreach ($details as $detail) {
            $merchandise = $merchandises->where('product_id', $detail['product_id'])->where('warehouse_id', $detail['warehouse_id'])->where($from, '>=', $detail['quantity'])->first();

            $merchandise->$from = $merchandise->$from - $detail['quantity'];

            $merchandise->save();
        }
    }

    public static function unavailableProducts($details, $in = 'available')
    {
        $unavailableProducts = collect();

        $merchandises = Merchandise::all();

        foreach ($details as $detail) {
            $availableMerchandises = $merchandises->where('product_id', $detail['product_id'])->where('warehouse_id', $detail['warehouse_id'])->where($in, '>=', $detail['quantity']);

            if ($availableMerchandises->isEmpty()) {
                $unavailableProducts->push(
                    "'{$detail->product->name}' is not available or not enough in '{$detail->warehouse->name}'"
                );
            }
        }

        return $unavailableProducts;
    }
}
