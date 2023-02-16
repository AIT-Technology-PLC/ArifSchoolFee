<?php

namespace App\Rules;

use App\Models\Gdn;
use App\Models\Returnn;
use Illuminate\Contracts\Validation\Rule;

class ValidateReturnQuantity implements Rule
{
    private $gdnID;

    public function __construct($gdnID)
    {
        $this->gdnID = $gdnID;
    }

    public function passes($attribute, $value)
    {
        $productID = request()->input(str_replace('.quantity', '.product_id', $attribute));

        $gdnQuantity = Gdn::firstWhere('id', $this->gdnID)->gdnDetails()->get()->firstWhere('product_id', $productID)->quantity ?? 0;

        $returns = Returnn::approved()->where('gdn_id', $this->gdnID)->get();

        $returnQuantity = 0;

        foreach ($returns as $return) {
            $returnQuantity += $return->returnDetails()->get()->where('product_id', $productID)?->sum('quantity');
        }

        $quantity = $gdnQuantity - $returnQuantity;

        if ($quantity > 0 && $value > $quantity) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return 'You can not return this much quantity for the above DO!';
    }
}
