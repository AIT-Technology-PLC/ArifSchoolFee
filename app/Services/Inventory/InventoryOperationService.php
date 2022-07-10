<?php

namespace App\Services\Inventory;

use App\Models\Merchandise;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Support\Arr;

class InventoryOperationService
{
    private static $properties = ['product_id', 'warehouse_id', 'quantity'];

    public static function add($details, $to = 'available')
    {
        $details = static::formatData($details);

        if (is_null($details)) {
            return;
        }

        foreach ($details as $detail) {
            $merchandise = Merchandise::firstOrCreate(
                [
                    'product_id' => $detail['product_id'],
                    'warehouse_id' => $detail['warehouse_id'],
                ],
                [
                    $to => 0.00,
                ]
            );

            $merchandise->$to = $merchandise->$to + $detail['quantity'];

            $merchandise->save();
        }
    }

    public static function subtract($details, $from = 'available')
    {
        $details = static::formatData($details);

        if (is_null($details)) {
            return;
        }

        $merchandises = Merchandise::all();

        foreach ($details as $detail) {
            $merchandise = $merchandises->where('product_id', $detail['product_id'])->where('warehouse_id', $detail['warehouse_id'])->where($from, '>=', $detail['quantity'])->first();

            $merchandise->$from = $merchandise->$from - $detail['quantity'];

            $merchandise->save();
        }
    }

    public static function unavailableProducts($details, $in = 'available')
    {
        $details = static::formatData($details);

        if (is_null($details)) {
            return;
        }

        $unavailableProducts = collect();

        $merchandises = Merchandise::all();
        $products = Product::all();
        $warehouses = Warehouse::all();

        foreach ($details as $detail) {
            $product = $products->find($detail['product_id']);
            $warehouse = $warehouses->find($detail['warehouse_id']);

            $availableMerchandises = $merchandises
                ->where('product_id', $detail['product_id'])
                ->where('warehouse_id', $detail['warehouse_id'])
                ->where($in, '>=', $detail['quantity']);

            if ($availableMerchandises->isEmpty()) {
                $unavailableProducts->push(
                    "'{$product->name}' is not available or not enough in '{$warehouse->name}'"
                );
            }
        }

        return $unavailableProducts;
    }

    public static function areAvailable($details, $in = 'available')
    {
        $details = static::formatData($details);

        if (is_null($details)) {
            return;
        }

        $unavailableProducts = collect();

        $merchandises = Merchandise::all();
        $products = Product::all();
        $warehouses = Warehouse::all();

        foreach ($details as $detail) {
            $product = $products->find($detail['product_id']);
            $warehouse = $warehouses->find($detail['warehouse_id']);

            $availableMerchandises = $merchandises
                ->where('product_id', $detail['product_id'])
                ->where('warehouse_id', $detail['warehouse_id'])
                ->where($in, '>=', $detail['quantity']);

            if ($availableMerchandises->isEmpty()) {
                $unavailableProducts->push([
                    'product' => $product,
                    'warehouse' => $warehouse,
                    'quantity' => $detail['quantity'],
                    $in => $merchandises
                        ->where('product_id', $detail['product_id'])
                        ->where('warehouse_id', $detail['warehouse_id'])
                        ->first()
                        ->$in,
                ]);
            }
        }

        return $unavailableProducts->isEmpty();
    }

    private static function formatData($details)
    {
        if (! is_countable($details) && ! Arr::has($details, static::$properties)) {
            return null;
        }

        if (Arr::has($details, static::$properties)) {
            $details = [$details];
        }

        if (collect($details)->filter(fn ($detail) => ! Arr::has($detail, static::$properties))->count()) {
            return null;
        }

        return $details;
    }
}
