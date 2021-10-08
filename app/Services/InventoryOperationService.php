<?php

namespace App\Services;

use App\Factory\InventoryTypeFactory;

class InventoryOperationService
{
    private static $unavailableProducts = [];

    public static function add($details, $to = 'available')
    {
        foreach ($details as $detail) {
            $type = InventoryTypeFactory::make($detail->product->type);

            $type->add(
                $detail->product_id,
                $detail->warehouse_id,
                $detail->quantity,
                $to
            );
        }
    }

    public static function subtract($details, $from = 'available')
    {
        foreach ($details as $detail) {
            $type = InventoryTypeFactory::make($detail->product->type);

            $type->subtract(
                $detail->product_id,
                $detail->warehouse_id,
                $detail->quantity,
                $from
            );
        }
    }

    public static function unavailableProducts($details, $in = 'available')
    {
        foreach ($details as $detail) {
            $type = InventoryTypeFactory::make($detail->product->type);

            if (!$type->isAvailable($detail->product_id, $detail->warehouse_id, $detail->quantity, $in)) {
                array_push(self::$unavailableProducts, "{$detail->product->name} is not available or not enough in {$detail->warehouse->name}");
            }
        }

        return collect(self::$unavailableProducts);
    }
}
