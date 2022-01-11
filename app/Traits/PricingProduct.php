<?php

namespace App\Traits;

trait PricingProduct
{
    public function getOriginalUnitPriceAttribute()
    {
        $inputtedUnitPrice = $this->unit_price;

        if (!userCompany()->isPriceBeforeVAT()) {
            $inputtedUnitPrice = $this->unit_price * 1.15;
        }

        return number_format($inputtedUnitPrice, 2, thousands_separator:'');
    }

    public function getUnitPriceAttribute($value)
    {
        if (!userCompany()->isPriceBeforeVAT()) {
            $value = $value / 1.15;
        }

        return number_format($value, 2, thousands_separator:'');
    }

    public function getTotalPriceAttribute()
    {
        $totalPrice = number_format($this->unit_price * $this->quantity, 2, thousands_separator:'');
        $discountAmount = 0.00;

        if (userCompany()->isDiscountBeforeVAT()) {
            $discountAmount = number_format($totalPrice * $this->discount, 2, thousands_separator:'');
        }

        return number_format($totalPrice - $discountAmount, 2, thousands_separator:'');
    }
}
