<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidateDeliveredQuantity implements ValidationRule
{
    private $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $merchandiseBatchId = request()->input(str_replace('.quantity', '.merchandise_batch_id', $attribute));
        $productId = request()->input(str_replace('.quantity', '.product_id', $attribute));
        $warehouseId = request()->input(str_replace('.quantity', '.warehouse_id', $attribute));

        $details = $this->model
            ->details()
            ->where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->when($merchandiseBatchId, fn($q) => $q->where('merchandise_batch_id', $merchandiseBatchId));

        $allowedQuantity = $details->sum('quantity') - $details->sum('delivered_quantity');

        if ($allowedQuantity < $value) {
            $fail('You can not convert more than the available quantity!');
        }
    }
}
