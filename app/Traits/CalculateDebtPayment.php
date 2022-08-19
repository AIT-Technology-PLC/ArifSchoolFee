<?php

namespace App\Traits;

trait CalculateDebtPayment
{
    public function isPaymentInCash()
    {
        return $this->payment_type == 'Cash Payment';
    }

    public function getDebtPayableInPercentageAttribute()
    {
        return 100.00 - $this->cash_payed_in_percentage;
    }

    public function getPaymentInCashAttribute()
    {
        $price = $this->grandTotalPrice;

        if ($this->cash_payed_type == 'percent') {
            $paymentInCash = $price * ($this->cash_payed_in_percentage / 100);
        }

        if ($this->cash_payed_type == 'amount') {
            $paymentInCash = $this->cash_payed;
        }

        return $paymentInCash;
    }

    public function getCashReceivedInPercentageAttribute()
    {
        $price = $this->grandTotalPrice;

        if ($price <= 0) {
            return 0.00;
        }

        if ($this->cash_payed_type == 'percent') {
            $cashReceivedInPercentage = $this->cash_payed;
        }

        if ($this->cash_payed_type == 'amount') {
            $cashReceivedInPercentage = ($this->paymentInCash / $price) * 100;
        }

        return $cashReceivedInPercentage;
    }

    public function getPaymentInDebtAttribute()
    {
        $price = $this->grandTotalPrice;

        return $price - $this->paymentInCash;
    }
}
