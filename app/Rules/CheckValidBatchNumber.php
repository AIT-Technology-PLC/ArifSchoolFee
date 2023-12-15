<?php

namespace App\Rules;

use App\Models\MerchandiseBatch;
use App\Models\Product;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckValidBatchNumber implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $productId = request()->input(str_replace('.merchandise_batch_id', '.product_id', $attribute));
        $warehouseId = request()->input(str_replace('.merchandise_batch_id', '.warehouse_id', $attribute));

        if (is_null($productId) || !Product::find($productId)->isBatchable()) {
            return;
        }

        $doesBatchExist = MerchandiseBatch::query()
            ->whereRelation('merchandise', 'product_id', $productId)
            ->when(!is_null($warehouseId), fn($q) => $q->whereRelation('merchandise', 'warehouse_id', $warehouseId))
            ->where('id', $value)
            ->exists();

        if (!$doesBatchExist) {
            $fail('Invalid Batch Number!');
        }
    }
}
