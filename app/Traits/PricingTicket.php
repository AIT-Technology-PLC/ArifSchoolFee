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
            $this->subtotalPrice * $this->tax_type,
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

    abstract public function details();
}
