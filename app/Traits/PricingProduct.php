<?php

namespace App\Traits;

trait PricingProduct
{
    public function getOriginalUnitPriceAttribute()
    {
        $inputtedUnitPrice = $this->unit_price;

        if (!userCompany()->isPriceBeforeVAT() && $this->product->tax->isVat()) {
            $inputtedUnitPrice = $this->unit_price * 1.15;
        }

        return number_format($inputtedUnitPrice, 2, thousands_separator:'');
    }

    public function getUnitPriceAttribute($value)
    {
        if (!userCompany()->isPriceBeforeVAT() && $this->product->tax->isVat()) {
            $value = $value / 1.15;
        }

        return number_format($value, 2, thousands_separator:'');
    }

    public function getTotalPriceAttribute()
    {
        $totalPrice = number_format($this->unit_price * $this->quantity, 2, thousands_separator:'');
        $discount = ($this->discount ?? 0.00) / 100;
        $discountAmount = 0.00;

        if (userCompany()->isDiscountBeforeVAT()) {
            $discountAmount = number_format($totalPrice * $discount, 2, thousands_separator:'');
        }

        return number_format($totalPrice - $discountAmount, 2, thousands_separator:'');
    }

    public function getTotalVatAttribute()
    {
        $totalVat = $this->Product->tax->amount * $this->totalPrice;

        return number_format($totalVat, 2, thousands_separator:'');
    }
}
