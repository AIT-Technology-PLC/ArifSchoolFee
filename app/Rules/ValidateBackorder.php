<?php

namespace App\Rules;

use App\Services\Inventory\InventoryOperationService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidateBackorder implements ValidationRule
{
    private $details;

    private $warehouseId;

    public function __construct($details = [], $warehouseId = null)
    {
        $this->details = collect($details);

        $this->warehouseId = $warehouseId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (userCompany()->isBackorderEnabled()) {
            return;
        }

        $warehouseId = !is_null($this->warehouseId) ? $this->warehouseId : request()->input(str_replace('.product_id', '.warehouse_id', $attribute));

        if (is_null($warehouseId) || is_null($value)) {
            return;
        }

        $totalRequestedQuantity = collect($this->details)
            ->where('product_id', $value)
            ->where('warehouse_id', $warehouseId)
            ->sum('quantity');

        $isAvailable = InventoryOperationService::areAvailable([
            'product_id' => $value,
            'quantity' => $totalRequestedQuantity,
            'warehouse_id' => $warehouseId,
        ]);

        if (!$isAvailable) {
            $fail('This Product is not available or not enough in the inventory.');
        }
    }
}
