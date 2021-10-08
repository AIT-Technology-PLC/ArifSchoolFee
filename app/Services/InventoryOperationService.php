<?php

namespace App\Services;

use App\Factory\InventoryTypeFactory;

class InventoryOperationService
{
    private static $unavailableProducts = [];

    public static function add($details)
    {
        foreach ($details as $detail) {
            $type = InventoryTypeFactory::make($detail->product->type);

            $type->add(
                $detail->product_id,
                $detail->warehouse_id,
                $detail->quantity
            );
        }
    }

    public static function subtract($details, $from)
    {
        if (static::unavailableProducts($details, $from)->isNotEmpty()) {
            return [
                'isSubtracted' => false,
                'unavailableProducts' => self::$unavailableProducts,
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
    }

    public static function transfer($details, $isSubtracted)
    {
        if (!$isSubtracted) {

            data_fill($details, '*.warehouse_id', $details->first()->transfer->transferred_from);

            data_fill($details, '*.warehouse', $details->first()->transfer->transferredFrom);

            if (static::unavailableProducts($details)->isNotEmpty()) {
                return [
                    'isTransferred' => false,
                    'unavailableProducts' => self::$unavailableProducts,
                ];
            }
        }

        foreach ($details as $detail) {
            $type = InventoryTypeFactory::make($detail->product->type);

            if ($isSubtracted) {
                $type->add(
                    $detail->product_id,
                    $detail->transfer->transferred_to,
                    $detail->quantity,
                );
            }

            if (!$isSubtracted) {
                $type->subtract(
                    $detail->product_id,
                    $detail->transfer->transferred_from,
                    $detail->quantity,
                );
            }
        }

        return ['isTransferred' => true];
    }

    public static function adjust($details)
    {
        if (static::unavailableProducts($details->where('is_subtract', 1))->isNotEmpty()) {
            return [
                'isAdjusted' => false,
                'unavailableProducts' => self::$unavailableProducts,
            ];
        }

        foreach ($details as $detail) {
            $type = InventoryTypeFactory::make($detail->product->type);

            if ($detail->is_subtract) {
                $type->subtract(
                    $detail->product_id,
                    $detail->warehouse_id,
                    $detail->quantity,
                );
            }

            if (!$detail->is_subtract) {
                $type->add(
                    $detail->product_id,
                    $detail->warehouse_id,
                    $detail->quantity,
                );
            }
        }

        return ['isAdjusted' => true];
    }

    public static function reserve($details)
    {
        if (static::unavailableProducts($details)->isNotEmpty()) {
            return [
                'isReserved' => false,
                'unavailableProducts' => self::$unavailableProducts,
            ];
        }

        foreach ($details as $detail) {
            $type = InventoryTypeFactory::make($detail->product->type);

            $type->subtract(
                $detail->product_id,
                $detail->warehouse_id,
                $detail->quantity,
            );

            $type->add(
                $detail->product_id,
                $detail->warehouse_id,
                $detail->quantity,
                'reserved'
            );
        }

        return ['isReserved' => true];
    }

    public static function cancelReservation($details)
    {
        foreach ($details as $detail) {
            $type = InventoryTypeFactory::make($detail->product->type);

            $type->subtract(
                $detail->product_id,
                $detail->warehouse_id,
                $detail->quantity,
                'reserved'
            );

            $type->add(
                $detail->product_id,
                $detail->warehouse_id,
                $detail->quantity,
            );
        }
    }

    public static function unavailableProducts($details, $in = 'available')
    {
        foreach ($details as $detail) {
            $type = InventoryTypeFactory::make($detail->product->type);

            if (!$type->isAvailable($detail->product_id, $detail->warehouse_id, $detail->quantity, $in)) {
                array_push(self::$unavailableProducts, "{$detail->product->name} is not available or not enough in {$detail->warehouse->name}");
            }
        }

        return collect(self::$unavailableProducts);
    }
}
