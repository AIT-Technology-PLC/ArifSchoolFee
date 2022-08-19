<?php

namespace App\Services\Inventory;

use App\Models\Merchandise;
use App\Models\MerchandiseBatch;
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

            static::addToBatch($detail, $merchandise);
        }
    }

    public static function addToBatch($detail, $merchandise)
    {
        if ($merchandise->product->isBatchable() && isset($detail['batch_no'])) {
            $merchandiseBatch = MerchandiseBatch::firstOrCreate(
                [
                    'merchandise_id' => $merchandise->id,
                    'batch_no' => $detail['batch_no'],
                ],
            );

            $merchandiseBatch->expiry_date = $detail['expiry_date'];
            $merchandiseBatch->quantity += $detail['quantity'];

            $merchandiseBatch->save();
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

            static::subtractFromBatch($detail, $merchandise);
        }
    }

    public static function subtractFromBatch($detail, $merchandise)
    {
        $merchandiseBatches = $merchandise->merchandiseBatches()->where('quantity', '>', 0)->get();

        if ($merchandise->product->isBatchable() && $merchandise->product->isLifo()) {
            $merchandiseBatches = $merchandiseBatches->sortByDesc('expiry_date');
        }

        if ($merchandise->product->isBatchable() && !$merchandise->product->isLifo()) {
            $merchandiseBatches = $merchandiseBatches->sortBy('expiry_date');
        }

        foreach ($merchandiseBatches as $merchandiseBatch) {
            if ($merchandiseBatch->quantity >= $detail['quantity']) {
                $merchandiseBatch->quantity -= $detail['quantity'];

                $merchandiseBatch->save();
                break;
            }

            if ($merchandiseBatch->quantity < $detail['quantity']) {
                $detail['quantity'] -= $merchandiseBatch->quantity;
                $merchandiseBatch->quantity = 0;

                $merchandiseBatch->save();
            }
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
                        ->$in ?? 0.00,
                ]);
            }
        }

        return $unavailableProducts->isEmpty();
    }

    private static function formatData($details)
    {
        if (!is_countable($details) && !Arr::has($details, static::$properties)) {
            return null;
        }

        if (Arr::has($details, static::$properties)) {
            $details = [$details];
        }

        if (collect($details)->filter(fn($detail) => !Arr::has($detail, static::$properties))->count()) {
            return null;
        }

        $products = Product::all();

        $inventoryTypeProducts = [];

        foreach ($details as $detail) {
            if (!$products->find($detail['product_id'])->isTypeService()) {
                $inventoryTypeProducts[] = $detail;
            }
        }

        return $inventoryTypeProducts;
    }
}
