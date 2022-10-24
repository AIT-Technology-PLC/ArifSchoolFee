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

    public function getVatAttribute()
    {
        return number_format(
            $this->subtotalPrice * 0.15,
            2,
            thousands_separator:''
        );
    }

    public function getGrandTotalPriceAttribute()
    {
        return number_format(
            $this->subtotalPrice + $this->vat,
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

    abstract public function details();
}
