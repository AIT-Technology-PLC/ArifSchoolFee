<?php

namespace App\Services\Inventory;

use App\Models\Merchandise;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Support\Arr;

class InventoryOperationService
{
    public static function add($details, $to = 'available')
    {
        if (!is_countable($details)) {
            return [false, 'The information submitted is not valid.'];
        }

        if (Arr::has($details, ['product_id', 'warehouse_id', 'quantity'])) {
            $details = [$details];
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
        if (!is_countable($details)) {
            return [false, 'The information submitted is not valid.'];
        }

        if (Arr::has($details, ['product_id', 'warehouse_id', 'quantity'])) {
            $details = [$details];
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
        if (!is_countable($details)) {
            return [false, 'The information submitted is not valid.'];
        }

        if (Arr::has($details, ['product_id', 'warehouse_id', 'quantity'])) {
            $details = [$details];
        }

        $unavailableProducts = collect();

        $merchandises = Merchandise::all();

        foreach ($details as $detail) {
            $product = Product::find($detail['product_id']);
            $warehouse = Warehouse::find($detail['warehouse_id']);

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
        if (!is_countable($details)) {
            return [false, 'The information submitted is not valid.'];
        }

        if (Arr::has($details, ['product_id', 'warehouse_id', 'quantity'])) {
            $details = [$details];
        }

        $unavailableProducts = collect();

        $merchandises = Merchandise::all();

        foreach ($details as $detail) {
            $product = Product::find($detail['product_id']);
            $warehouse = Warehouse::find($detail['warehouse_id']);

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
}
