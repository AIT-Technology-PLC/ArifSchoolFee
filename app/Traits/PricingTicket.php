<?php

namespace App\Traits;

trait PricingTicket
{
    public function getSubtotalPriceAttribute()
    {
        return $this->details()->sum->totalPrice;
    }

    public function getVatAttribute()
    {
        return $this->subtotalPrice * 0.15;
    }

    public function getGrandTotalPriceAttribute()
    {
        return $this->subtotalPrice + $this->vat;
    }

    public function getGrandTotalPriceAfterDiscountAttribute()
    {
        return $this->grandTotalPrice - ($this->grandTotalPrice * $this->discount);
    }

    abstract public function details();
}
