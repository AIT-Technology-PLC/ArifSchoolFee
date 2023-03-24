<?php

namespace App\Rules;

use App\Models\MerchandiseBatch;
use App\Models\Product;
use Illuminate\Contracts\Validation\Rule;

class CheckBatchQuantity implements Rule
{
    public function __construct($details)
    {
        $this->details = $details;
    }

    public function passes($attribute, $value)
    {
        $merchandiseBatchId = request()->input(str_replace('.quantity', '.merchandise_batch_id', $attribute));
        $productId = request()->input(str_replace('.quantity', '.product_id', $attribute));
        $warehouseId = request()->input(str_replace('.quantity', '.warehouse_id', $attribute));

        $totalRequestedQuantity = collect($this->details)
            ->where('product_id', $productId)
            ->when(!is_null($warehouseId), fn($q) => $q->where('warehouse_id', $warehouseId))
            ->when(!is_null($merchandiseBatchId), fn($q) => $q->where('merchandise_batch_id', $merchandiseBatchId))
            ->sum('quantity');

        if (!is_null($productId) && Product::find($productId)->isBatchable()) {
            return MerchandiseBatch::query()
                ->whereRelation('merchandise', 'product_id', $productId)
                ->when(!is_null($warehouseId), fn($q) => $q->whereRelation('merchandise', 'warehouse_id', $warehouseId))
                ->when(!is_null($merchandiseBatchId), fn($q) => $q->where('id', $merchandiseBatchId))
                ->sum('quantity') >= $totalRequestedQuantity;
        }

        return true;
    }

    public function message()
    {
        return 'There is no sufficient amount in this batch, please check your inventory.';
    }
}
