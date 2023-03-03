<?php

namespace App\Rules;

use App\Services\Inventory\InventoryOperationService;
use Illuminate\Contracts\Validation\Rule;

class ValidateBackorder implements Rule
{
    public function __construct($details = [])
    {
        $this->details = collect($details);
    }

    public function passes($attribute, $value)
    {
        if (userCompany()->isBackorderEnabled()) {
            return true;
        }

        $warehouseId = request()->input(str_replace('.product_id', '.warehouse_id', $attribute));

        if (is_null($warehouseId) || is_null($value)) {
            return true;
        }

        $totalRequestedQuantity = collect($this->details)
            ->where('product_id', $value)
            ->where('warehouse_id', $warehouseId)
            ->sum('quantity');

        return InventoryOperationService::areAvailable([
            'product_id' => $value,
            'quantity' => $totalRequestedQuantity,
            'warehouse_id' => $warehouseId,
        ]);
    }

    public function message()
    {
        return "This Product is not available or not enough in the inventory.";
    }
}
