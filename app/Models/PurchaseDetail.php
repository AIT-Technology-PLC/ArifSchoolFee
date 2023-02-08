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
        $totalPrice = number_format($this->unit_price * $this->quantity * ($this->purchase->exchange_rate ?? 1), 2, thousands_separator:'');
        $discount = ($this->discount ?? 0.00) / 100;
        $discountAmount = 0.00;

        if (userCompany()->isDiscountBeforeTax()) {
            $discountAmount = number_format($totalPrice * $discount, 2, thousands_separator:'');
        }

        return number_format($totalPrice - $discountAmount, 2, thousands_separator:'');
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

        if ($this->purchase->purchaseDetails->sum('amount') == 0) {
            return $this->purchase->purchaseDetails->sum('amount');
        }

        return ($this->amount * $this->purchase->freight_cost) / $this->purchase->purchaseDetails->sum('amount');
    }

    public function getFreightInsuranceCostValueAttribute()
    {
        if (!$this->purchase->isImported()) {
            return 0;
        }

        if ($this->purchase->purchaseDetails->sum('amount') == 0) {
            return $this->purchase->purchaseDetails->sum('amount');
        }

        return ($this->amount * $this->purchase->freight_insurance_cost) / $this->purchase->purchaseDetails->sum('amount');
    }

    public function getOtherCostValueAttribute()
    {
        if (!$this->purchase->isImported()) {
            return 0;
        }

        if ($this->purchase->purchaseDetails->sum('amount') == 0) {
            return $this->purchase->purchaseDetails->sum('amount');
        }

        return ($this->amount * $this->purchase->other_costs) / $this->purchase->purchaseDetails->sum('amount');
    }

    public function getDutyPayingValueAttribute()
    {
        if ($this->purchase->type == 'Import') {
            return ($this->unitPriceInLocalCurrency * $this->quantity) + $this->freightCostValue + $this->freightInsuranceCostValue + $this->otherCostValue;
        }

        return 0;
    }

    public function getCustomDutyTaxAttribute()
    {
        if ($this->purchase->type == 'Import') {
            return $this->dutyPayingValue * ($this->duty_rate / 100);
        }

        return 0;
    }

    public function getExciseTaxAmountAttribute()
    {
        if ($this->purchase->type == 'Import') {
            return $this->excise_tax / 100 * ($this->dutyPayingValue + $this->customDutyTax);
        }

        return 0;
    }

    public function getValueAddedTaxAttribute()
    {
        if ($this->purchase->type == 'Import') {
            return $this->vat_rate / 100 * ($this->dutyPayingValue + $this->customDutyTax + $this->exciseTaxAmount);
        }

        return 0;
    }

    public function getSurtaxAmountAttribute()
    {
        if ($this->purchase->type == 'Import') {
            return $this->surtax / 100 * ($this->dutyPayingValue + $this->customDutyTax + $this->exciseTaxAmount + $this->valueAddedTax);
        }

        return 0;
    }

    public function getWithHoldingTaxAmountAttribute()
    {
        if ($this->purchase->type == 'Import') {
            return $this->withholding_tax / 100 * $this->dutyPayingValue;
        }

        return 0;
    }

    public function getTotalPayableTaxAttribute()
    {
        if ($this->purchase->type == 'Import') {
            return $this->customDutyTax + $this->exciseTaxAmount + $this->valueAddedTax + $this->surtaxAmount + $this->withHoldingTaxAmount;
        }

        return 0;
    }

    public function getTotalCostAfterTaxAttribute()
    {
        if ($this->purchase->type == 'Import') {
            return $this->dutyPayingValue + $this->totalPayableTax + $this->purchase->local_other_costs;
        }

        return 0;
    }

    public function getUnitCostAfterTaxAttribute()
    {
        if ($this->purchase->type == 'Import') {
            return $this->totalCostAfterTax / ($this->quantity ?? 1);
        }

        return 0;
    }
}
