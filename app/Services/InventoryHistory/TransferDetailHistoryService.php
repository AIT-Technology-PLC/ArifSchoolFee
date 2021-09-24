<?php

namespace App\Services\InventoryHistory;

use App\Interfaces\DetailHistoryServiceInterface;
use App\Models\TransferDetail;
use Illuminate\Support\Str;

class TransferDetailHistoryService implements DetailHistoryServiceInterface
{
    public static function get($warehouse, $product)
    {
        return (new TransferDetail())->getByWarehouseAndProduct($warehouse, $product);
    }

    public static function format($transferDetails)
    {
        return $transferDetails->map(function ($transferDetail) {
            return [
                'type' => 'TRANSFER',
                'code' => $transferDetail->transfer->code,
                'date' => $transferDetail->transfer->issued_on,
                'quantity' => $transferDetail->quantity,
                'balance' => 0.00,
                'unit_of_measurement' => $transferDetail->product->unit_of_measurement,

                'details' => $transferDetail->transfer->transferred_from == $this->warehouse->id ?
                Str::of('Transferred')->append(...[' from ', $transferDetail->transfer->transferredFrom->name]) :
                Str::of('Transferred')->append(...[' to ', $transferDetail->transfer->transferredTo->name]),

                'function' => $transferDetail->transfer->transferred_from == $this->warehouse->id ? 'subtract' : 'add',
            ];
        });
    }

    public static function formatted($warehouse, $product)
    {
        $transferDetails = self::get($warehouse, $product);

        return self::format($transferDetails);
    }
}
