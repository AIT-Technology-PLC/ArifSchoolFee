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
        if (!static::isAvailable($details, $from)) {
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

            if (!static::isAvailable($details)) {
                return [
                    'isTransferred' => false,
                    'unavailableProducts' => self::$unavailableProducts,
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
    }

    public static function adjust($details)
    {
        if (!static::isAvailable($details)) {
            return [
                'isAdjusted' => false,
                'unavailableProducts' => self::$unavailableProducts,
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
    }

    public static function reserve($details)
    {
        if (!static::isAvailable($details)) {
            return [
                'isReserved' => false,
                'unavailableProducts' => self::$unavailableProducts,
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
    }

    public static function cancelReservation($details)
    {
        foreach ($details as $detail) {
            $type = InventoryTypeFactory::make($detail->product->type);

            $type->cancelReservation(
                $detail->product_id,
                $detail->warehouse_id,
                $detail->quantity,
            );
        }
    }

    public static function isAvailable($details, $in = 'available')
    {
        foreach ($details as $detail) {
            $type = InventoryTypeFactory::make($detail->product->type);

            if (!$type->isAvailable($detail->product_id, $detail->warehouse_id, $detail->quantity, $in)) {
                array_push(self::$unavailableProducts, "{$detail->product->name} is not available or not enough in {$detail->warehouse->name}");
            }
        }

        return count(self::$unavailableProducts) ? false : true;
    }
}
