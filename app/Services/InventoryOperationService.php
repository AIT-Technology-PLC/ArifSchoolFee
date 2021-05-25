<?php

namespace App\Services;

use App\Factory\InventoryTypeFactory;
use Illuminate\Support\Facades\DB;

class InventoryOperationService
{
    public static function add($details)
    {
        DB::transaction(function () use ($details) {
            foreach ($details as $detail) {
                $type = InventoryTypeFactory::make($detail);

                $type->add($detail);
            }
        });
    }

    public static function subtract($details)
    {
        $result = DB::transaction(function () use ($details) {
            $unavailableProducts = [];

            foreach ($details as $detail) {
                $type = InventoryTypeFactory::make($detail);

                if (!$type->isAvailable($detail)) {
                    array_push($unavailableProducts, $detail->product->name . 'is not available or not enough in ' . $detail->warehouse->name . '.');
                }
            }

            if (count($unavailableProducts)) {
                return [
                    'isSubtracted' => false,
                    'unavailableProducts' => $unavailableProducts,
                ];
            }

            foreach ($details as $detail) {
                $type = InventoryTypeFactory::make($detail);

                $type->subtract($detail);
            }

            return ['isSubtracted' => true];
        });

        return $result;
    }
}
