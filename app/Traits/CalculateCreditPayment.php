<?php

namespace App\Traits;

trait CalculateCreditPayment
{
    public function isPaymentInCash()
    {
        return $this->payment_type == 'Cash Payment';
    }

    public function getCreditPayableInPercentageAttribute()
    {
        return 100.00 - $this->cash_received_in_percentage;
    }

    public function getPaymentInCashAttribute()
    {
        if (userCompany()->isDiscountBeforeVAT()) {
            $price = $this->grandTotalPrice;
        }

        if (! userCompany()->isDiscountBeforeVAT()) {
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
        if (userCompany()->isDiscountBeforeVAT()) {
            $price = $this->grandTotalPrice;
        }

        if (! userCompany()->isDiscountBeforeVAT()) {
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

        return $cashReceivedInPercentage;
    }

    public function getPaymentInCreditAttribute()
    {
        if (userCompany()->isDiscountBeforeVAT()) {
            $price = $this->grandTotalPrice;
        }

        if (! userCompany()->isDiscountBeforeVAT()) {
            $price = $this->grandTotalPriceAfterDiscount;
        }

        return $price - $this->paymentInCash;
    }
}
