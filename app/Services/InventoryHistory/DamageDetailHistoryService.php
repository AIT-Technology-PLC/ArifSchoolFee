<?php

namespace App\Services\InventoryHistory;

use App\Interfaces\DetailHistoryServiceInterface;
use App\Models\DamageDetail;

class DamageDetailHistoryService implements DetailHistoryServiceInterface
{
    private static $warehouse, $product;

    public static function get($warehouse, $product)
    {
        return (new DamageDetail())->getByWarehouseAndProduct($warehouse, $product);
    }

    public static function format($damageDetails)
    {
        return $damageDetails->map(function ($damageDetail) {
            return [
                'type' => 'DAMAGE',
                'code' => $damageDetail->damage->code,
                'date' => $damageDetail->damage->issued_on,
                'quantity' => $damageDetail->quantity,
                'balance' => 0.00,
                'unit_of_measurement' => static::$product->unit_of_measurement,
                'details' => 'Damaged in ' . static::$warehouse->name,
                'function' => 'subtract',
            ];
        });
    }

    public static function formatted($warehouse, $product)
    {
        static::$product = $product;

        static::$warehouse = $warehouse;

        $damageDetails = self::get($warehouse, $product);

        return self::format($damageDetails);
    }
}
