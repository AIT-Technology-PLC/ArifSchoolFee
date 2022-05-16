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
            $paymntInCash = $price * ($this->cash_received / 100);
        }

        if ($this->cash_received_type == 'amount') {
            $paymntInCash = $this->cash_received;
        }

        return $paymntInCash;
    }

    public function getPaymentPercentInCashAttribute()
    {
        if (userCompany()->isDiscountBeforeVAT()) {
            $price = $this->grandTotalPrice;
        }

        if (!userCompany()->isDiscountBeforeVAT()) {
            $price = $this->grandTotalPriceAfterDiscount;
        }

        if ($this->cash_received < 0) {
            return $price;
        }

        if ($this->cash_received_type == 'percent') {
            $paymentPercentInCash = $this->cash_received;
        }

        if ($this->cash_received_type == 'amount') {
            $paymentPercentInCash = ($this->cash_received * 100) / $price;
        }

        return $paymentPercentInCash;
    }

    public function getPaymentInCreditAttribute()
    {
        if (userCompany()->isDiscountBeforeVAT()) {
            $price = $this->grandTotalPrice;
        }

        if (!userCompany()->isDiscountBeforeVAT()) {
            $price = $this->grandTotalPriceAfterDiscount;
        }

        if ($this->credit_payable_in_percentage < 0) {
            return $price;
        }

        return $price * ($this->credit_payable_in_percentage / 100);
    }
}
