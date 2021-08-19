<?php
namespace App\Traits;

trait PricingProduct
{
    public function getOriginalUnitPriceAttribute()
    {
        return $this->unit_price * 1.15;
    }

    public function getUnitPriceAttribute($value)
    {
        if (userCompany()->is_price_before_vat) {
            return $value;
        }

        return $value / 1.15;
    }

    public function getTotalPriceAttribute()
    {
        if (userCompany()->is_discount_before_vat) {
            return ($this->unit_price * $this->quantity) -
                (($this->unit_price * $this->quantity) * $this->discount);
        }

        return $this->unit_price * $this->quantity;
    }
}
