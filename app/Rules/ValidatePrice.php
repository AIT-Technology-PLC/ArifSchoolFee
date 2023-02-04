<?php

namespace App\Rules;

use App\Models\Price;
use App\Models\Product;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;

class ValidatePrice implements Rule
{
    private $details;

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

        $haveActivePrices = Price::active()->where('product_id', $productId)->exists();

        if (!$haveActivePrices) {
            return true;
        }

        $this->message = 'The selected price is incorrect.';

        return Price::active()->where('product_id', $productId)->where('fixed_price', $value)->exists();
    }

    public function message()
    {
        return $this->message;
    }
}
