<?php

namespace App\Traits;

trait CalculateCreditPayment
{
    public function isPaymentInCredit()
    {
        return $this->payment_type == 'Credit Payment';
    }

    public function getCreditPayableInPercentageAttribute()
    {
        return 100.00 - $this->cash_received_in_percentage - $this->withholdingTaxInPercentage;
    }

    public function getPaymentInCashAttribute()
    {
        if (userCompany()->isDiscountBeforeTax()) {
            $price = $this->grandTotalPrice;
        }

        if (!userCompany()->isDiscountBeforeTax()) {
            $price = $this->grandTotalPriceAfterDiscount;
        }

        if ($this->cash_received_type == 'percent') {
            $paymentInCash = $price * ($this->cash_received_in_percentage / 100);
        }

        if ($this->cash_received_type == 'amount') {
            $paymentInCash = $this->cash_received;
        }

        return $paymentInCash;
    }

    public function getCashReceivedInPercentageAttribute()
    {
        if (userCompany()->isDiscountBeforeTax()) {
            $price = $this->grandTotalPrice;
        }

        if (!userCompany()->isDiscountBeforeTax()) {
            $price = $this->grandTotalPriceAfterDiscount;
        }

        if ($price <= 0) {
            return 0.00;
        }

        if ($this->cash_received_type == 'percent') {
            $cashReceivedInPercentage = $this->cash_received;
        }

        if ($this->cash_received_type == 'amount') {
            $cashReceivedInPercentage = ($this->paymentInCash / $price) * 100;
        }

        if (!$this->isPaymentInCredit()) {
            $cashReceivedInPercentage -= $this->withholdingTaxInPercentage;
        }

        return $cashReceivedInPercentage;
    }

    public function getPaymentInCreditAttribute()
    {
        if (userCompany()->isDiscountBeforeTax()) {
            $price = $this->grandTotalPrice;
        }

        if (!userCompany()->isDiscountBeforeTax()) {
            $price = $this->grandTotalPriceAfterDiscount;
        }

        return $price - $this->paymentInCash - $this->totalWithheldAmount;
    }

    public function getWithholdingTaxInPercentageAttribute()
    {
        if (!$this->hasWithholding()) {
            return 0.00;
        }

        if (userCompany()->isDiscountBeforeTax()) {
            $price = $this->grandTotalPrice;
        }

        if (!userCompany()->isDiscountBeforeTax()) {
            $price = $this->grandTotalPriceAfterDiscount;
        }

        if ($price <= 0) {
            return 0.00;
        }

        return $this->totalWithheldAmount / $price * 100;
    }

    public function hasWithholding()
    {
        return false;
    }
}
