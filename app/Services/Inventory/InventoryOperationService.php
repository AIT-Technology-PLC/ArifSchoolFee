<?php

namespace App\Services\Inventory;

use App\Factory\InventoryTypeFactory;

class InventoryOperationService
{
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
        $unavailableProducts = collect();

        foreach ($details as $detail) {
            $type = InventoryTypeFactory::make($detail->product->type);

            if (!$type->isAvailable($detail->product_id, $detail->warehouse_id, $detail->quantity, $in)) {
                $unavailableProducts->push(
                    "'{$detail->product->name}' is not available or not enough in '{$detail->warehouse->name}'"
                );
            }
        }

        return $unavailableProducts;
    }
}
