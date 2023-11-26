<?php

namespace App\Traits;

trait PricingTicket
{
    public function getSubtotalPriceAttribute()
    {
        return number_format(
            $this->details()->sum->totalPrice,
            2,
            thousands_separator:''
        );
    }

    public function getTaxAttribute()
    {
        return number_format(
            $this->details()->sum->totalTax,
            2,
            thousands_separator:''
        );
    }

    public function getGrandTotalPriceAttribute()
    {
        return number_format(
            $this->subtotalPrice + $this->tax,
            2,
            thousands_separator:''
        );
    }

    public function getGrandTotalPriceAfterDiscountAttribute()
    {
        $discount = ($this->discount ?? 0.00) / 100;
        $discountAmount = number_format($this->grandTotalPrice * $discount, 2, thousands_separator:'');

        return number_format(
            $this->grandTotalPrice - $discountAmount,
            2,
            thousands_separator:''
        );
    }

    public function hasDiscount()
    {
        if (userCompany()->isDiscountBeforeTax()) {
            return $this->details()->sum->discount > 0;
        }

        return $this->discount > 0;
    }

    abstract public function details();
}
