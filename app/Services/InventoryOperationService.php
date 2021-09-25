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
                $type = InventoryTypeFactory::make($detail->product->type);

                $type->add(
                    $detail->product_id,
                    $detail->warehouse_id,
                    $detail->quantity
                );
            }
        });
    }

    public static function subtract($details, $from)
    {
        $result = DB::transaction(function () use ($details, $from) {
            $unavailableProducts = [];

            foreach ($details as $detail) {
                $type = InventoryTypeFactory::make($detail->product->type);

                if (!$type->isAvailable($detail->product_id, $detail->warehouse_id, $detail->quantity, $from)) {
                    array_push($unavailableProducts, $detail->product->name . ' is not available or not enough in ' . $detail->warehouse->name . '.');
                }
            }

            if (count($unavailableProducts)) {
                return [
                    'isSubtracted' => false,
                    'unavailableProducts' => $unavailableProducts,
                ];
            }

            foreach ($details as $detail) {
                $type = InventoryTypeFactory::make($detail->product->type);

                $type->subtract(
                    $detail->product_id,
                    $detail->warehouse_id,
                    $detail->quantity,
                    $from
                );
            }

            return ['isSubtracted' => true];
        });

        return $result;
    }

    public static function transfer($details, $isSubtracted)
    {
        $result = DB::transaction(function () use ($details, $isSubtracted) {
            if (!$isSubtracted) {
                $unavailableProducts = [];

                foreach ($details as $detail) {
                    $type = InventoryTypeFactory::make($detail->product->type);

                    if (!$type->isAvailable($detail->product_id, $detail->transfer->transferred_from, $detail->quantity)) {
                        array_push($unavailableProducts, $detail->product->name . ' is not available or not enough in ' . $detail->transfer->transferredFrom->name . '.');
                    }
                }

                if (count($unavailableProducts)) {
                    return [
                        'isTransferred' => false,
                        'unavailableProducts' => $unavailableProducts,
                    ];
                }
            }

            foreach ($details as $detail) {
                $type = InventoryTypeFactory::make($detail->product->type);

                $type->transfer(
                    $detail->product_id,
                    $detail->transfer->transferred_to,
                    $detail->transfer->transferred_from,
                    $detail->quantity,
                    $isSubtracted
                );
            }

            return ['isTransferred' => true];
        });

        return $result;
    }

    public static function adjust($details)
    {
        $result = DB::transaction(function () use ($details) {
            $unavailableProducts = [];

            foreach ($details as $detail) {
                $type = InventoryTypeFactory::make($detail->product->type);

                if ($detail->is_subtract && !$type->isAvailable($detail->product_id, $detail->warehouse_id, $detail->quantity)) {
                    array_push($unavailableProducts, $detail->product->name . ' is not available or not enough in ' . $detail->warehouse->name . '.');
                }
            }

            if (count($unavailableProducts)) {
                return [
                    'isAdjusted' => false,
                    'unavailableProducts' => $unavailableProducts,
                ];
            }

            foreach ($details as $detail) {
                $type = InventoryTypeFactory::make($detail->product->type);

                $type->adjust(
                    $detail->product_id,
                    $detail->warehouse_id,
                    $detail->quantity,
                    $detail->is_subtract
                );
            }

            return ['isAdjusted' => true];
        });

        return $result;
    }

    public static function reserve($details)
    {
        $result = DB::transaction(function () use ($details) {
            $unavailableProducts = [];

            foreach ($details as $detail) {
                $type = InventoryTypeFactory::make($detail->product->type);

                if (!$type->isAvailable($detail->product_id, $detail->warehouse_id, $detail->quantity)) {
                    array_push($unavailableProducts, $detail->product->name . ' is not available or not enough in ' . $detail->warehouse->name . '.');
                }
            }

            if (count($unavailableProducts)) {
                return [
                    'isReserved' => false,
                    'unavailableProducts' => $unavailableProducts,
                ];
            }

            foreach ($details as $detail) {
                $type = InventoryTypeFactory::make($detail->product->type);

                $type->reserve(
                    $detail->product_id,
                    $detail->warehouse_id,
                    $detail->quantity,
                );
            }

            return ['isReserved' => true];
        });

        return $result;
    }

    public static function cancelReservation($details)
    {
        DB::transaction(function () use ($details) {
            foreach ($details as $detail) {
                $type = InventoryTypeFactory::make($detail->product->type);

                $type->cancelReservation(
                    $detail->product_id,
                    $detail->warehouse_id,
                    $detail->quantity,
                );
            }
        });
    }
}
