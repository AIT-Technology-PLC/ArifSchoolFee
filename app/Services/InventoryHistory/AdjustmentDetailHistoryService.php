<?php

namespace App\Services\InventoryHistory;

use App\Interfaces\DetailHistoryServiceInterface;
use App\Models\AdjustmentDetail;

class AdjustmentDetailHistoryService implements DetailHistoryServiceInterface
{
    private static $warehouse, $product;

    public static function get($warehouse, $product)
    {
        return (new AdjustmentDetail())->getByWarehouseAndProduct($warehouse, $product);
    }

    public static function format($adjustmentDetails)
    {
        return $adjustmentDetails->map(function ($adjustmentDetail) {
            return [
                'type' => 'ADJUSTMENT',
                'code' => $adjustmentDetail->adjustment->code,
                'date' => $adjustmentDetail->adjustment->issued_on,
                'quantity' => $adjustmentDetail->quantity,
                'balance' => 0.00,
                'unit_of_measurement' => static::$product->unit_of_measurement,
                'details' => ($adjustmentDetail->is_subtract ? 'Subtracted' : 'Added') . ' in ' . static::$warehouse->name,
                'function' => $adjustmentDetail->is_subtract ? 'subtract' : 'add',
            ];
        });
    }

    public static function formatted($warehouse, $product)
    {
        static::$product = $product;

        static::$warehouse = $warehouse;

        $adjustmentDetails = self::get($warehouse, $product);

        return self::format($adjustmentDetails);
    }
}
