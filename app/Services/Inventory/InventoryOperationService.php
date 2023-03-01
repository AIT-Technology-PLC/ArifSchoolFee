<?php

namespace App\Services\Inventory;

use App\Models\InventoryHistory;
use App\Models\Merchandise;
use App\Models\MerchandiseBatch;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class InventoryOperationService
{
    private static $properties = ['product_id', 'warehouse_id', 'quantity'];

    public static function add($details, $model, $to = 'available')
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

            static::createInventoryHistory($model, $detail, false);
        }
    }

    public static function subtract($details, $model, $from = 'available')
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

            static::createInventoryHistory($model, $detail);
        }
    }

    public static function addToBatch($detail, $merchandise)
    {
        $batchNo = $detail['batch_no'] ?? $detail['merchandiseBatch']['batch_no'] ?? null;

        if (!$merchandise->product->isBatchable() || is_null($batchNo)) {
            return;
        }

        $merchandiseBatch = MerchandiseBatch::firstOrCreate([
            'merchandise_id' => $merchandise->id,
            'batch_no' => $batchNo,
        ]);

        $merchandiseBatch->expires_on = $detail['expires_on'] ?? $detail['merchandiseBatch']['expires_on'] ?? null;
        $merchandiseBatch->quantity += $detail['quantity'];

        if (isset($detail['transfer_id'])) {
            $merchandiseBatch->received_quantity += $detail['quantity'];
        } else {
            $merchandiseBatch->received_quantity += $detail['merchandise_batch_id'] ? 0 : $detail['quantity'];
        }

        $merchandiseBatch->save();
    }

    public static function subtractFromBatch($detail, $merchandise)
    {
        if (!$merchandise->product->isBatchable() || empty($detail['merchandiseBatch'])) {
            return;
        }

        $merchandiseBatch = $merchandise->merchandiseBatches()->firstWhere('id', $detail['merchandise_batch_id']);

        $merchandiseBatch->quantity -= $detail['quantity'];

        $merchandiseBatch->save();
    }

    public static function createInventoryHistory($model, $detail, $isSubtract = true)
    {
        if (is_null($model)) {
            return;
        }

        $inventoryHistoryDetail = [
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'issued_on' => $model->issued_on,
            'is_subtract' => $isSubtract ? 1 : 0,
        ];

        if ($detail instanceof Model) {
            $detail = $detail->only(static::$properties);
        }

        InventoryHistory::create(
            Arr::only($detail, static::$properties) + $inventoryHistoryDetail
        );
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

            if ($availableMerchandises->isNotEmpty()) {
                $availableMerchandises->first()[$in] -= $detail['quantity'];
            }

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

            if ($availableMerchandises->isNotEmpty()) {
                $availableMerchandises->first()[$in] -= $detail['quantity'];
            }

            if ($availableMerchandises->isEmpty()) {
                $unavailableProducts->push([
                    'product' => $product,
                    'warehouse' => $warehouse,
                    'quantity' => $detail['quantity'],
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
