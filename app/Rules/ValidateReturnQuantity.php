<?php

namespace App\Rules;

use App\Models\GdnDetail;
use Illuminate\Contracts\Validation\Rule;

class ValidateReturnQuantity implements Rule
{
    private $gdnId;

    private $details;

    public function __construct($gdnId = null, $details)
    {
        $this->gdnId = $gdnId;

        $this->details = $details;
    }

    public function passes($attribute, $value)
    {
        if (!userCompany()->isReturnLimitedBySales() || is_null($this->gdnId)) {
            return true;
        }

        $productId = request()->input(str_replace('.quantity', '.product_id', $attribute));

        $totalRequestedQuantity = collect($this->details)->where('product_id', $productId)->sum('quantity');

        $gdnTotalQuantity = GdnDetail::query()
            ->where('gdn_id', $this->gdnId)
            ->where('product_id', $productId)
            ->sum('quantity');

        $gdnTotalReturnedQuantity = GdnDetail::query()
            ->where('gdn_id', $this->gdnId)
            ->where('product_id', $productId)
            ->sum('returned_quantity');

        $allowedQuantity = $gdnTotalQuantity - $gdnTotalReturnedQuantity;

        return $allowedQuantity >= $totalRequestedQuantity;
    }

    public function message()
    {
        return 'You can not return this much quantity for the above Delivery Order!';
    }
}
