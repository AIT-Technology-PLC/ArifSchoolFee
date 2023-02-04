<?php

namespace App\Rules;

use App\Models\Price;
use App\Models\Product;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;

class ValidatePrice implements Rule
{
    public function __construct($details = [])
    {
        $this->details = $details;
    }

    public function passes($attribute, $value)
    {
        $productIdKey = str_replace('.unit_price', '.product_id', $attribute);

        $productId = request()->input($productIdKey) ?? Arr::get($this->details, $productIdKey);

        if (Product::where('id', $productId)->doesntExist()) {

            return false;
        }

        $hasPrices = Price::where('product_id', $productId)->whereIn('fixed_price', $value)->exist();

        return $hasPrices;
    }

    public function message()
    {
        return "The selected price is incorrect.";
    }
}
