<?php

namespace App\Actions;

use App\Models\MerchandiseBatch;
use App\Models\Product;

class PadAutoBatchStoringAction
{
    public static function execute($transaction)
    {
        if (userCompany()->canSelectBatchNumberOnForms() || $transaction->pad->isInventoryOperationAdd()) {
            return;
        }

        $occupiedMerchandiseBatches = [];

        $batchableProductIds = Product::batchable()->pluck('id');

        $deletableTransactionFieldsLines = $transaction->getTransactionDetails()->whereIn('product_id', $batchableProductIds)->pluck('line')->unique();

        $transactionDetails = $transaction->getTransactionDetails()->whereIn('product_id', $batchableProductIds);

        foreach ($transactionDetails as $detail) {
            $baseTransactionFields = $transaction
                ->transactionFields()
                ->where('line', $detail['line'])
                ->whereRelation('padField', 'label', '<>', 'Quantity')
                ->get(['pad_field_id', 'value'])
                ->toArray();

            $product = Product::find($detail['product_id']);

            $merchandiseBatches = MerchandiseBatch::available()
                ->whereNotIn('id', $occupiedMerchandiseBatches)
                ->whereRelation('merchandise', 'product_id', $detail['product_id'])
                ->when(isset($detail['warehouse_id']), fn($q) => $q->whereRelation('merchandise', 'warehouse_id', $detail['warehouse_id']))
                ->when($product->isLifo(), fn($q) => $q->orderBy('expires_on', 'DESC'))
                ->when(!$product->isLifo(), fn($q) => $q->orderBy('expires_on', 'ASC'))
                ->get();

            foreach ($merchandiseBatches as $merchandiseBatch) {
                $line = $transaction->transactionFields()->max('line') + 1;
                $data = $baseTransactionFields;

                $merchandiseBatchField['pad_field_id'] = $transaction->pad->padFields()->firstWhere('label', 'Batch')->id;
                $merchandiseBatchField['value'] = $merchandiseBatch->id;

                $data[] = $merchandiseBatchField;

                $originalQuantity = $detail['quantity'];

                $detail['quantity'] = $merchandiseBatch->quantity >= $detail['quantity'] ? $detail['quantity'] : $merchandiseBatch->quantity;

                $quantityField['pad_field_id'] = $transaction->pad->padFields()->firstWhere('label', 'Quantity')->id;
                $quantityField['value'] = $detail['quantity'];

                $data[] = $quantityField;

                data_fill($data, '*.line', $line);

                $transaction->transactionFields()->createMany($data);

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

        $transaction->transactionFields()->whereIn('line', $deletableTransactionFieldsLines)->forceDelete();
    }
}
