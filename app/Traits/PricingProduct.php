<?php

namespace App\Traits;

trait PricingProduct
{
    public function getOriginalUnitPriceAttribute()
    {
        if (userCompany()->isPriceBeforeVAT()) {
            return $this->unit_price;
        }

        return $this->unit_price * 1.15;
    }

    public function getUnitPriceAttribute($value)
    {
        if (userCompany()->isPriceBeforeVAT()) {
            return $value;
        }

        return $value / 1.15;
    }

    public function getTotalPriceAttribute()
    {
        $totalPrice = $this->unit_price * $this->quantity;

        if (userCompany()->isDiscountBeforeVAT()) {
            return $totalPrice - ($totalPrice * $this->discount);
        }

        return $totalPrice;
    }
}
