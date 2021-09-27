<?php

namespace App\Services\InventoryHistory;

use App\Interfaces\DetailHistoryServiceInterface;
use App\Models\ReservationDetail;
use Illuminate\Support\Str;

class ReservationDetailHistoryService implements DetailHistoryServiceInterface
{
    private static $warehouse, $product;

    public static function get($warehouse, $product)
    {
        return (new ReservationDetail())->getByWarehouseAndProduct($warehouse, $product);
    }

    public static function format($reservationDetails)
    {
        return $reservationDetails->map(function ($reservationDetail) {
            return [
                'type' => 'RESERVED',
                'code' => $reservationDetail->reservation->code,
                'date' => $reservationDetail->reservation->issued_on,
                'quantity' => $reservationDetail->quantity,
                'balance' => 0.00,
                'unit_of_measurement' => static::$product->unit_of_measurement,
                'details' => Str::of($reservationDetail->reservation->customer->company_name ?? 'Unknown')->prepend('Reserved for '),
                'function' => 'subtract',
            ];
        });
    }

    public static function formatted($warehouse, $product)
    {
        static::$product = $product;

        static::$warehouse = $warehouse;

        $reservationDetails = self::get($warehouse, $product);

        return self::format($reservationDetails);
    }
}
