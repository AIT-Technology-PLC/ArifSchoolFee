<?php

namespace App\Rules;

use App\Models\Price;
use App\Models\Product;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;

class ValidatePrice implements Rule
{
    private $message;

    public function __construct($details = [])
    {
        $this->details = $details;
    }

    public function passes($attribute, $value)
    {
        $productIdKey = str_replace('.unit_price', '.product_id', $attribute);

        $productId = request()->input($productIdKey) ?? Arr::get($this->details, $productIdKey);

        if (Product::where('id', $productId)->doesntExist()) {
            $this->message = 'The selected product does not exist.';

            return false;
        }

        $price = Price::firstWhere('product_id', $productId);

        if (! $price) {
            return true;
        }

        if ($price->isFixed() && $value != $price->fixed_price) {
            $this->message = "The price of this product should be {$price->fixed_price}.";

            return false;
        }

        if (! $price->isFixed() && ($value < $price->min_price || $value > $price->max_price)) {
            $this->message = "The price of this product should be a minimum of {$price->min_price} or a maximum of {$price->max_price}.";

            return false;
        }

        return true;
    }

    public function message()
    {
        return $this->message;
    }
}
