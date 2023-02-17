<?php

namespace App\Rules;

use App\Models\GdnDetail;
use App\Models\ReturnDetail;
use Illuminate\Contracts\Validation\Rule;

class ValidateReturnQuantity implements Rule
{
    private $gdnId;

    public function __construct($gdnId)
    {
        $this->gdnId = $gdnId;
    }

    public function passes($attribute, $value)
    {
        $productId = request()->input(str_replace('.quantity', '.product_id', $attribute));

        $gdnQuantity = GdnDetail::where('gdn_id', $this->gdnId)->where('product_id', $productId)->sum('quantity');

        $returnedQuantity = ReturnDetail::query()
            ->whereRelation('returnn', 'gdn_id', $this->gdnId)
            ->whereRelation('returnn', 'approved_by', '<>', null)
            ->where('product_id', $productId)
            ->sum('quantity');

        $allowedQuantity = $gdnQuantity - $returnedQuantity;

        return $allowedQuantity >= $value;
    }

    public function message()
    {
        return 'You can not return this much quantity for the above DO!';
    }
}
