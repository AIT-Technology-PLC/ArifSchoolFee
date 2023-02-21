<?php

namespace App\Rules;

use App\Models\GdnDetail;
use App\Models\ReturnDetail;
use Illuminate\Contracts\Validation\Rule;

class ValidateReturnQuantity implements Rule
{
    private $gdnId;

    private $details;

    public function __construct($gdnId, $details)
    {
        $this->gdnId = $gdnId;

        $this->details = $details;
    }

    public function passes($attribute, $value)
    {
        $totalQuantity = 0;
        $productId = request()->input(str_replace('.quantity', '.product_id', $attribute));

        foreach ($this->details as $detail) {
            if ($detail['product_id'] == $productId) {
                $totalQuantity += $detail['quantity'];
            }
        }

        $gdnQuantity = GdnDetail::where('gdn_id', $this->gdnId)->where('product_id', $productId)->sum('quantity');

        $returnedQuantity = ReturnDetail::query()
            ->whereRelation('returnn', 'gdn_id', $this->gdnId)
            ->whereRelation('returnn', 'added_by', '<>', null)
            ->where('product_id', $productId)
            ->sum('quantity');

        $allowedQuantity = $gdnQuantity - $returnedQuantity;

        return $allowedQuantity >= $totalQuantity;
    }

    public function message()
    {
        return 'You can not return this much quantity for the above DO!';
    }
}