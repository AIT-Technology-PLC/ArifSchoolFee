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

    public static function format($transferDetails, $warehouse = null)
    {
        return $transferDetails
            ->filter(function ($transferDetail) use ($warehouse) {
                if ($transferDetail->transfer->transferred_to == $warehouse->id && !$transferDetail->transfer->isAdded()) {
                    return false;
                }

                return true;
            })
            ->map(function ($transferDetail) use ($warehouse) {
                return [
                    'type' => 'TRANSFER',
                    'code' => $transferDetail->transfer->code,
                    'date' => $transferDetail->transfer->issued_on,
                    'quantity' => $transferDetail->quantity,
                    'balance' => 0.00,
                    'unit_of_measurement' => $transferDetail->product->unit_of_measurement,

                    'details' => $transferDetail->transfer->transferred_from == $warehouse->id ?
                    Str::of('Transferred')->append(' from ', $transferDetail->transfer->transferredFrom->name) :
                    Str::of('Transferred')->append(' to ', $transferDetail->transfer->transferredTo->name),

                    'function' => $transferDetail->transfer->transferred_from == $warehouse->id ? 'subtract' : 'add',
                ];
            });
    }

    public static function formatted($warehouse, $product)
    {
        $transferDetails = self::get($warehouse, $product);

        return self::format($transferDetails, $warehouse);
    }
}
