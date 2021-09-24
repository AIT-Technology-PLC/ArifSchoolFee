<?php

namespace App\Services\InventoryHistory;

use App\Interfaces\DetailHistoryServiceInterface;
use App\Models\GrnDetail;
use Illuminate\Support\Str;

class GrnDetailHistoryService implements DetailHistoryServiceInterface
{
    public static function get($warehouse, $product)
    {
        return (new GrnDetail())->getByWarehouseAndProduct($warehouse, $product);
    }

    public static function format($grnDetails)
    {
        return $grnDetails->map(function ($grnDetail) {
            return [
                'type' => 'GRN',
                'code' => $grnDetail->grn->code,
                'date' => $grnDetail->grn->issued_on,
                'quantity' => $grnDetail->quantity,
                'balance' => 0.00,
                'unit_of_measurement' => $grnDetail->product->unit_of_measurement,
                'details' => Str::of($grnDetail->grn->supplier->company_name ?? 'Unknown')->prepend('Purchased from '),
                'function' => 'add',
            ];
        });
    }

    public static function formatted($warehouse, $product)
    {
        $grnDetails = self::get($warehouse, $product);

        return self::format($grnDetails);
    }
}
