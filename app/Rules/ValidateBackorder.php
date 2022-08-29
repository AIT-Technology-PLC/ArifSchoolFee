<?php

namespace App\Rules;

use App\Models\Merchandise;
use Illuminate\Contracts\Validation\Rule;

class ValidateBackorder implements Rule
{
    private $message;

    private $quantity;

    private $warehouseId;

    public function __construct($quantity = null, $warehouseId = null)
    {
        $this->quantity = $quantity;

        $this->warehouseId = $warehouseId;
    }

    public function passes($attribute, $value)
    {
        $productId = $value;
        $quantity = $this->quantity;
        $warehouseId = $this->warehouseId;

        if (!userCompany()->isBackorderEnabled()) {
            if (is_null($warehouseId)) {
                $merchandises = Merchandise::where('company_id', userCompany()->id)->whereIn('warehouse_id', authUser()->getAllowedWarehouses('create')->pluck('id'))->where('product_id', $productId)->get();
            }

            if (!is_null($warehouseId)) {
                $warehouseIdKey = str_replace('.product_id', '.warehouse_id', $attribute);
                $warehouseId = request()->input($warehouseIdKey);

                $merchandises = Merchandise::where('company_id', userCompany()->id)->whereIn('warehouse_id', authUser()->getAllowedWarehouses('create')->pluck('id'))->where('warehouse_id', $warehouseId)->where('product_id', $productId)->get();
            }

            if (!is_null($merchandises) && count($merchandises) > 0) {
                $totalAvailable = 0;
                foreach ($merchandises as $merchandise) {
                    $totalAvailable += $merchandise->available;
                }

                $quantityKey = str_replace('.product_id', '.quantity', $attribute);
                $quantity = request()->input($quantityKey);

                if ($totalAvailable < $quantity) {
                    $this->message = 'This Product is out of stock.';

                    return false;
                }
            }
        }

        return true;
    }

    public function message()
    {
        return $this->message;
    }
}