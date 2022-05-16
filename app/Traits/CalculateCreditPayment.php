<?php

namespace App\Traits;

trait CalculateCreditPayment
{
    public function getCreditPayableInPercentageAttribute()
    {
        if ($this->cash_received_type == 'percent') {
            $percent = 100.00 - $this->cash_received;

        }

        if ($this->cash_received_type == 'amount') {
            $percent = 100.00 - (($this->cash_received * 100) / $this->grandTotalPriceAfterDiscount);
        }

        return $percent;
    }

    public function getPaymentInCashAttribute()
    {
        if (userCompany()->isDiscountBeforeVAT()) {
            $price = $this->grandTotalPrice;
        }

        if (!userCompany()->isDiscountBeforeVAT()) {
            $price = $this->grandTotalPriceAfterDiscount;
        }

        if ($this->cash_received_type == 'percent') {
            $paymentInCash = $price * ($this->cash_received / 100);
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

        if (!userCompany()->isDiscountBeforeVAT()) {
            $price = $this->grandTotalPriceAfterDiscount;
        }

        if ($this->cash_received_type == 'percent') {
            $cashReceivedInPercentage = $this->cash_received;
        }

        if ($this->cash_received_type == 'amount') {
            $cashReceivedInPercentage = ($this->cash_received * 100) / $price;
        }

        return $cashReceivedInPercentage;
    }

    public function getPaymentInCreditAttribute()
    {
        if (userCompany()->isDiscountBeforeVAT()) {
            $price = $this->grandTotalPrice;
        }

        if (!userCompany()->isDiscountBeforeVAT()) {
            $price = $this->grandTotalPriceAfterDiscount;
        }

        return $price - $this->paymentInCash;
    }
}
