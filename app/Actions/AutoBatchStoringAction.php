<?php

namespace App\Actions;

use App\Models\MerchandiseBatch;
use App\Models\Product;

class AutoBatchStoringAction
{
    public static function execute($model, $data, $detailModel)
    {
        if (userCompany()->canSelectBatchNumberOnForms()) {
            return;
        }

        $occupiedMerchandiseBatches = [];

        $model->{$detailModel}()->whereRelation('product', 'is_batchable', 1)->whereNull('merchandise_batch_id')->forceDelete();

        foreach ($data as $detail) {
            $product = Product::find($detail['product_id']);

            if ($product->isBatchable()) {
                $merchandiseBatches = MerchandiseBatch::available()
                    ->whereNotIn('id', $occupiedMerchandiseBatches)
                    ->whereRelation('merchandise', 'product_id', $detail['product_id'])
                    ->when(isset($detail['warehouse_id']), fn($q) => $q->whereRelation('merchandise', 'warehouse_id', $detail['warehouse_id']))
                    ->when($product->isLifo(), fn($q) => $q->orderBy('expires_on', 'DESC'))
                    ->when(!$product->isLifo(), fn($q) => $q->orderBy('expires_on', 'ASC'))
                    ->get();

                foreach ($merchandiseBatches as $merchandiseBatch) {
                    $detail['merchandise_batch_id'] = $merchandiseBatch->id;

                    $originalQuantity = $detail['quantity'];

                    $detail['quantity'] = $merchandiseBatch->quantity >= $detail['quantity'] ? $detail['quantity'] : $merchandiseBatch->quantity;

                    $model->$detailModel()->create($detail);

                    $detail['quantity'] = $originalQuantity;

                    if ($detail['quantity'] >= $merchandiseBatch->quantity) {
                        $occupiedMerchandiseBatches[] = $merchandiseBatch->id;
                    }

                    if ($merchandiseBatch->quantity >= $detail['quantity']) {
                        break;
                    }

                    if ($detail['quantity'] > $merchandiseBatch->quantity) {
                        $difference = $detail['quantity'] - $merchandiseBatch->quantity;
                        $detail['quantity'] = $difference;
                    }
                }
            }
        }
    }
}
