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

    public static function subtract($details, $from)
    {
        $result = DB::transaction(function () use ($details, $from) {
            $unavailableProducts = [];

            foreach ($details as $detail) {
                $type = InventoryTypeFactory::make($detail);

                if (!$type->isAvailable($detail, $from)) {
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
                $type = InventoryTypeFactory::make($detail);

                $type->subtract($detail, $from);
            }

            return ['isSubtracted' => true];
        });

        return $result;
    }

    public static function transfer($details)
    {
        $result = DB::transaction(function () use ($details) {
            $unavailableProducts = [];

            foreach ($details as $detail) {
                $type = InventoryTypeFactory::make($detail);

                if (!$type->isAvailable($detail)) {
                    array_push($unavailableProducts, $detail->product->name . ' is not available or not enough in ' . $detail->warehouse->name . '.');
                }
            }

            if (count($unavailableProducts)) {
                return [
                    'isTransferred' => false,
                    'unavailableProducts' => $unavailableProducts,
                ];
            }

            foreach ($details as $detail) {
                $type = InventoryTypeFactory::make($detail);

                $type->transfer($detail);
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
                $type = InventoryTypeFactory::make($detail);

                if ($detail->is_subtract && !$type->isAvailable($detail)) {
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
                $type = InventoryTypeFactory::make($detail);

                $type->adjust($detail);
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
                $type = InventoryTypeFactory::make($detail);

                if (!$type->isAvailable($detail)) {
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
                $type = InventoryTypeFactory::make($detail);

                $type->reserve($detail);
            }

            return ['isReserved' => true];
        });

        return $result;
    }

    public static function cancelReservation($details)
    {
        DB::transaction(function () use ($details) {
            foreach ($details as $detail) {
                $type = InventoryTypeFactory::make($detail);

                $type->cancelReservation($detail);
            }
        });
    }
}
