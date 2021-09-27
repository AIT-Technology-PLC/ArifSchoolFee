<?php

namespace App\Services\InventoryHistory;

use App\Interfaces\DetailHistoryServiceInterface;
use App\Models\ReturnDetail;

class ReturnDetailHistoryService implements DetailHistoryServiceInterface
{
    private static $warehouse, $product;

    public static function get($warehouse, $product)
    {
        return (new ReturnDetail())->getByWarehouseAndProduct($warehouse, $product);
    }

    public static function format($returnDetails)
    {
        return $returnDetails->map(function ($returnDetail) {
            return [
                'type' => 'RETURN',
                'code' => $returnDetail->returnn->code,
                'date' => $returnDetail->returnn->issued_on,
                'quantity' => $returnDetail->quantity,
                'balance' => 0.00,
                'unit_of_measurement' => static::$product->unit_of_measurement,
                'details' => 'Returned to ' . static::$warehouse->name . ' from' . ($returnDetail->returnn->customer->company_name ?? ' Unknown'),
                'function' => 'add',
            ];
        });
    }

    public static function formatted($warehouse, $product)
    {
        static::$product = $product;

        static::$warehouse = $warehouse;

        $returnDetails = self::get($warehouse, $product);

        return self::format($returnDetails);
    }
}
