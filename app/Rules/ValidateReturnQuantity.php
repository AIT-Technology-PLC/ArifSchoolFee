<?php

namespace App\Rules;

use App\Models\GdnDetail;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidateReturnQuantity implements ValidationRule
{
    private $gdnId;

    private $details;

    public function __construct($gdnId, $details)
    {
        $this->gdnId = $gdnId;

        $this->details = $details;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!userCompany()->isReturnLimitedBySales() || is_null($this->gdnId)) {
            return;
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

        if ($allowedQuantity < $totalRequestedQuantity) {
            $fail('You can not return this much quantity for the above Delivery Order!');
        }
    }
}
