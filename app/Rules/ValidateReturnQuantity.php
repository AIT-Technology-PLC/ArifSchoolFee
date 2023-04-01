<?php

namespace App\Rules;

use App\Models\GdnDetail;
use Illuminate\Contracts\Validation\Rule;

class ValidateReturnQuantity implements Rule
{
    private $gdnId;

    private $customerId;

    private $details;

    public function __construct($gdnId = null, $customerId = null, $details)
    {
        $this->gdnId = $gdnId;

        $this->customerId = $customerId;

        $this->details = $details;
    }

    public function passes($attribute, $value)
    {
        $productId = request()->input(str_replace('.quantity', '.product_id', $attribute));

        $totalRequestedQuantity = collect($this->details)->where('product_id', $productId)->sum('quantity');

        $gdnTotalQuantity = GdnDetail::query()
            ->when(!is_null($this->customerId), fn($q) => $q->whereRelation('gdn', 'customer_id', $this->customerId))
            ->when(!is_null($this->gdnId), fn($q) => $q->where('gdn_id', $this->gdnId))
            ->where('product_id', $productId)
            ->sum('quantity');

        $gdnTotalReturnedQuantity = GdnDetail::query()
            ->when(!is_null($this->customerId), fn($q) => $q->whereRelation('gdn', 'customer_id', $this->customerId))
            ->when(!is_null($this->gdnId), fn($q) => $q->where('gdn_id', $this->gdnId))
            ->where('product_id', $productId)
            ->sum('returned_quantity');

        $allowedQuantity = $gdnTotalQuantity - $gdnTotalReturnedQuantity;

        return $allowedQuantity >= $totalRequestedQuantity;
    }

    public function message()
    {
        return 'You can not return this much quantity for the above DO or Customer!';
    }
}
