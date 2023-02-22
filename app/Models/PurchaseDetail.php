<?php

namespace App\Models;

use App\Traits\PricingProduct;
use App\Traits\TouchParentUserstamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseDetail extends Model
{
    use SoftDeletes, TouchParentUserstamp, PricingProduct;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function parentModel()
    {
        return $this->purchase;
    }

    public function getUnitPriceAttribute($value)
    {
        return $value;
    }

    public function getTotalPriceAttribute()
    {
        if ($this->purchase->isImported()) {
            return $this->unitPriceInLocalCurrency * $this->quantity;
        }

        $totalPrice = number_format($this->unit_price * $this->quantity, 2, thousands_separator:'');
        $discount = ($this->discount ?? 0.00) / 100;
        $discountAmount = 0.00;

        if (userCompany()->isDiscountBeforeTax()) {
            $discountAmount = number_format($totalPrice * $discount, 2, thousands_separator:'');
        }

        return number_format($totalPrice - $discountAmount, 2, thousands_separator:'');
    }

    public function getTotalPriceInForeignCurrencyAttribute()
    {
        if (!$this->purchase->isImported()) {
            return 0;
        }

        return $this->unit_price * $this->quantity;
    }

    public function getUnitPriceInLocalCurrencyAttribute()
    {
        return $this->unit_price * $this->purchase->exchange_rate;
    }

    public function getFreightCostValueAttribute()
    {
        if (!$this->purchase->isImported()) {
            return 0;
        }

        if ($this->purchase->purchaseDetails->sum('amount') == 0 && userCompany()->isFreightAmountByVolume()) {
            return $this->purchase->purchaseDetails->sum('amount');
        }

        if (!userCompany()->isFreightAmountByVolume()) {
            return ($this->totalPriceInForeignCurrency / $this->purchase->purchaseDetails->sum('total_Price_In_Foreign_Currency')) * $this->purchase->freight_cost;
        }

        return ($this->amount * $this->purchase->freight_cost) / $this->purchase->purchaseDetails->sum('amount');
    }

    public function getFreightInsuranceCostValueAttribute()
    {
        if (!$this->purchase->isImported()) {
            return 0;
        }

        if ($this->purchase->purchaseDetails->sum('amount') == 0 && userCompany()->isFreightAmountByVolume()) {
            return $this->purchase->purchaseDetails->sum('amount');
        }

        if (!userCompany()->isFreightAmountByVolume()) {
            return ($this->totalPriceInForeignCurrency / $this->purchase->purchaseDetails->sum('total_Price_In_Foreign_Currency')) * $this->purchase->freight_insurance_cost;
        }

        return ($this->amount * $this->purchase->freight_insurance_cost) / $this->purchase->purchaseDetails->sum('amount');
    }

    public function getOtherCostBeforeTaxValueAttribute()
    {
        if (!$this->purchase->isImported()) {
            return 0;
        }

        if ($this->purchase->purchaseDetails->sum('amount') == 0 && userCompany()->isFreightAmountByVolume()) {
            return $this->purchase->purchaseDetails->sum('amount');
        }

        if (!userCompany()->isFreightAmountByVolume()) {
            return ($this->totalPriceInForeignCurrency / $this->purchase->purchaseDetails->sum('total_Price_In_Foreign_Currency')) * $this->purchase->other_costs_before_tax;
        }

        return ($this->amount * $this->purchase->other_costs_before_tax) / $this->purchase->purchaseDetails->sum('amount');
    }

    public function getOtherCostAfterTaxValueAttribute()
    {
        if (!$this->purchase->isImported()) {
            return 0;
        }

        if ($this->purchase->purchaseDetails->sum('amount') == 0 && userCompany()->isFreightAmountByVolume()) {
            return $this->purchase->purchaseDetails->sum('amount');
        }

        if (!userCompany()->isFreightAmountByVolume()) {
            return ($this->totalPriceInForeignCurrency / $this->purchase->purchaseDetails->sum('total_Price_In_Foreign_Currency')) * $this->purchase->other_costs_after_tax;
        }

        return ($this->amount * $this->purchase->other_costs_after_tax) / $this->purchase->purchaseDetails->sum('amount');
    }

    public function getDutyPayingValueAttribute()
    {
        if (!$this->purchase->isImported()) {
            return 0;
        }

        return ($this->unitPriceInLocalCurrency * $this->quantity) + $this->freightCostValue + $this->freightInsuranceCostValue + $this->otherCostBeforeTaxValue;
    }

    public function getCustomDutyTaxAttribute()
    {
        if (!$this->purchase->isImported()) {
            return 0;
        }

        return $this->dutyPayingValue * ($this->duty_rate / 100);
    }

    public function getExciseTaxAmountAttribute()
    {
        if (!$this->purchase->isImported()) {
            return 0;
        }

        return $this->excise_tax / 100 * ($this->dutyPayingValue + $this->customDutyTax);
    }

    public function getValueAddedTaxAttribute()
    {
        if (!$this->purchase->isImported()) {
            return 0;
        }

        return $this->vat_rate / 100 * ($this->dutyPayingValue + $this->customDutyTax + $this->exciseTaxAmount);
    }

    public function getSurtaxAmountAttribute()
    {
        if (!$this->purchase->isImported()) {
            return 0;
        }

        return $this->surtax / 100 * ($this->dutyPayingValue + $this->customDutyTax + $this->exciseTaxAmount + $this->valueAddedTax);
    }

    public function getWithHoldingTaxAmountAttribute()
    {
        if (!$this->purchase->isImported()) {
            return 0;
        }

        return $this->withholding_tax / 100 * $this->dutyPayingValue;
    }

    public function getTotalPayableTaxAttribute()
    {
        if (!$this->purchase->isImported()) {
            return 0;
        }

        return $this->customDutyTax + $this->exciseTaxAmount + $this->valueAddedTax + $this->surtaxAmount + $this->withHoldingTaxAmount;
    }

    public function getTotalCostAfterTaxAttribute()
    {
        if (!$this->purchase->isImported()) {
            return 0;
        }

        return $this->dutyPayingValue + $this->totalPayableTax + $this->otherCostAfterTaxValue;
    }

    public function getUnitCostAfterTaxAttribute()
    {
        if (!$this->purchase->isImported()) {
            return 0;
        }

        return $this->totalCostAfterTax / ($this->quantity ?? 1);
    }
}
