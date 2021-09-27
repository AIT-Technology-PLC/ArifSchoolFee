<?php

namespace App\Services\InventoryHistory;

use App\Interfaces\DetailHistoryServiceInterface;
use App\Models\GdnDetail;
use Illuminate\Support\Str;

class GdnDetailHistoryService implements DetailHistoryServiceInterface
{
    private static $warehouse, $product;

    public static function get($warehouse, $product)
    {
        return (new GdnDetail())->getByWarehouseAndProduct($warehouse, $product);
    }

    public static function format($gdnDetails)
    {
        return $gdnDetails->map(function ($gdnDetail) {
            return [
                'type' => 'DO',
                'code' => $gdnDetail->gdn->code,
                'date' => $gdnDetail->gdn->issued_on,
                'quantity' => $gdnDetail->quantity,
                'balance' => 0.00,
                'unit_of_measurement' => static::$product->unit_of_measurement,
                'details' => Str::of($gdnDetail->gdn->customer->company_name ?? 'Unknown')->prepend('Submitted to '),
                'function' => 'subtract',
            ];
        });
    }

    public static function formatted($warehouse, $product)
    {
        static::$product = $product;

        static::$warehouse = $warehouse;

        $gdnDetails = self::get($warehouse, $product);

        return self::format($gdnDetails);
    }
}
