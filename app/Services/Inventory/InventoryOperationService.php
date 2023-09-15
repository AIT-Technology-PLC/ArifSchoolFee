<?php

namespace App\Services\Inventory;

use App\Models\InventoryHistory;
use App\Models\InventoryValuationBalance;
use App\Models\Merchandise;
use App\Models\MerchandiseBatch;
use App\Models\Product;
use App\Models\Warehouse;
use App\Utilities\InventoryValuationCalculator;
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

            static::addToBatch($detail, $merchandise, $to);

            static::createInventoryHistory($model, $detail, $to, false);

            if ($model->canAffectInventoryValuation() && $to == 'available') {
                InventoryValuationCalculator::calculate($detail, 'add');
            }
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

            static::subtractFromBatch($detail, $merchandise, $from);

            static::createInventoryHistory($model, $detail, $from);

            if ($model->canAffectInventoryValuation() && $from == 'available') {
                static::subtractFromInventoryValuationBalance($detail);
                InventoryValuationCalculator::calculate($detail);
            }
        }
    }

    public static function addToBatch($detail, $merchandise, $to)
    {
        $batchNo = $detail['batch_no'] ?? $detail['merchandiseBatch']['batch_no'] ?? null;

        if ($to != 'available' || !$merchandise->product->isBatchable() || is_null($batchNo)) {
            return;
        }

        $merchandiseBatch = MerchandiseBatch::firstOrCreate(
            [
                'merchandise_id' => $merchandise->id,
                'batch_no' => $batchNo,
            ],
            [
                'received_quantity' => $detail['quantity'],
                'quantity' => 0.00,
            ]
        );

        $merchandiseBatch->expires_on = $detail['expires_on'] ?? $detail['merchandiseBatch']['expires_on'] ?? null;

        $merchandiseBatch->quantity += $detail['quantity'];

        $merchandiseBatch->save();
    }

    public static function subtractFromBatch($detail, $merchandise, $from)
    {
        if ($from != 'available' || !$merchandise->product->isBatchable() || empty($detail['merchandise_batch_id'])) {
            return;
        }

        $merchandiseBatch = $merchandise->merchandiseBatches()->firstWhere('id', $detail['merchandise_batch_id']);

        $merchandiseBatch->quantity -= $detail['quantity'];

        $merchandiseBatch->save();
    }

    private static function subtractFromInventoryValuationBalance($detail)
    {
        foreach (['fifo', 'lifo'] as $method) {
            $quantity = $detail['quantity'];

            $inventoryValuationBalances = InventoryValuationBalance::available()->where('product_id', $detail['product_id'])
                ->where('type', $method)
                ->orderBy('id', $method == 'fifo' ? 'asc' : 'desc')
                ->get();

            foreach ($inventoryValuationBalances as $inventoryValuationBalance) {
                if ($inventoryValuationBalance->quantity >= $quantity) {
                    $inventoryValuationBalance->quantity -= $quantity;
                    $inventoryValuationBalance->save();

                    break;
                }

                if ($inventoryValuationBalance->quantity < $quantity) {
                    $quantity = $quantity - $inventoryValuationBalance->quantity;
                    $inventoryValuationBalance->save();
                }
            }
        }
    }

    public static function createInventoryHistory($model, $detail, $fromOrTo, $isSubtract = true)
    {
        if ($fromOrTo != 'available') {
            return;
        }

        $inventoryHistoryDetail = [
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'model_detail_type' => $detail['model_type'] ?? get_class($detail),
            'model_detail_id' => $detail['model_id'] ?? $detail->id,
            'issued_on' => $model->issued_on,
            'is_subtract' => $isSubtract ? 1 : 0,
        ];

        if (!auth()->check()) {
            $inventoryHistoryDetail['company_id'] = $model->company_id;
        }

        if ($detail instanceof Model) {
            $detail = $detail->only(static::$properties);
        }

        InventoryHistory::firstOrCreate(
            Arr::only($inventoryHistoryDetail, ['model_detail_type', 'model_detail_id']),
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

        return $unavailableProducts
            ->push(...static::unavailableProductsByBatch($details))
            ->filter();
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

    public static function unavailableProductsByBatch($details)
    {
        $unavailableProducts = collect();

        $merchandiseBatches = MerchandiseBatch::whereHas('merchandise')->with('merchandise')->get();

        $batchableProducts = Product::batchable()->get();

        foreach ($details as $detail) {
            if (!$batchableProducts->contains($detail['product_id'])) {
                continue;
            }

            $merchandiseBatch = $merchandiseBatches->find($detail['merchandise_batch_id']);

            if ($merchandiseBatch->quantity >= $detail['quantity']) {
                $merchandiseBatch->quantity -= $detail['quantity'];
                continue;
            }

            if ($merchandiseBatch->quantity < $detail['quantity']) {
                $unavailableProducts->push(
                    "'{$merchandiseBatch->merchandise->product->name}' is not available or not enough in batch no:'{$merchandiseBatch->batch_no}'"
                );
            }
        }

        return $unavailableProducts;
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
            if ($products->find($detail['product_id'])->isTypeProduct()) {
                $inventoryTypeProducts[] = $detail;
            }
        }

        return $inventoryTypeProducts;
    }
}
