<?php

namespace App\Traits;

trait Discountable
{
    public function getDiscountAttribute($value)
    {
        if (is_null($value)) {
            return 0;
        }

        return $value / 100;
    }
}
