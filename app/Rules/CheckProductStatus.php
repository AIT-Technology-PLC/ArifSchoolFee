<?php

namespace App\Rules;

use App\Models\Product;
use Illuminate\Contracts\Validation\Rule;

class CheckProductStatus implements Rule
{
    public function __construct()
    {
        //
    }

    public function passes($attribute, $value)
    {
        if (Product::activeForSale()->where('id', $value)->doesntExist()) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return 'This product is not used for sale.';
    }
}
