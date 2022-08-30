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
        $warehouseIdKey = str_replace('.product_id', '.warehouse_id', $attribute);
        $warehouseId = request()->input($warehouseIdKey);

        $quantityKey = str_replace('.product_id', '.quantity', $attribute);
        $quantity = request()->input($quantityKey);
        $isAvailable = InventoryOperationService::areAvailable(['product_id' => $value, 'quantity' => $quantity, 'warehouse_id' => $warehouseId]);

        return $isAvailable;
    }

    public function message()
    {
        return "This Product is out of stock";
    }
}