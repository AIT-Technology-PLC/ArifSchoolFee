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
        if (userCompany()->isPriceBeforeVAT()) {
            return $value;
        }

        return $value / 1.15;
    }

    public function getTotalPriceAttribute()
    {
        if (userCompany()->isDiscountBeforeVAT()) {
            return ($this->unit_price * $this->quantity) -
                (($this->unit_price * $this->quantity) * $this->discount);
        }

        return $this->unit_price * $this->quantity;
    }
}
