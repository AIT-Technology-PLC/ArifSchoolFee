<?php

namespace App\Rules;

use App\Models\MerchandiseBatch;
use App\Models\Product;
use Illuminate\Contracts\Validation\Rule;

class CheckValidBatchNumber implements Rule
{
    public function passes($attribute, $value)
    {
        $productId = request()->input(str_replace('.merchandise_batch_id', '.product_id', $attribute));
        $warehouseId = request()->input(str_replace('.merchandise_batch_id', '.warehouse_id', $attribute));

        if (is_null($productId) || !Product::find($productId)->isBatchable()) {
            return true;
        }

        return MerchandiseBatch::query()
            ->whereRelation('merchandise', 'product_id', $productId)
            ->when(!is_null($warehouseId), fn($q) => $q->whereRelation('merchandise', 'warehouse_id', $warehouseId))
            ->where('id', $value)
            ->exists();
    }

    public function message()
    {
        return 'Invalid Batch Number!';
    }
}
