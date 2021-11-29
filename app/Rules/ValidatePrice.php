<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidatePrice implements Rule
{
    private $productId, $message;

    public function __construct($productId)
    {
        $this->productId = $productId;
    }

    public function passes($attribute, $value)
    {
        $price = Price::firstWhere('product_id', $this->productId);

        if (!$price) {
            return true;
        }

        if ($price->isFixed() && $value != $price->fixed_price) {
            $this->message = "The price of this product should be {$price->fixed_price}";

            return false;
        }

        if (!$price->isFixed() && ($value < $price->min_price || $value > $price->max_price)) {
            $this->message = "The price of this product should a minimum of {$price->min_price} or a maximum of {$price->min_price}";

            return false;
        }

        return true;
    }

    public function message()
    {
        return $this->message;
    }
}
