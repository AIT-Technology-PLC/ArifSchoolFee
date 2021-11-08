<?php

namespace App\Traits;

trait CalculateCreditPayment
{
    public function getCreditPayableInPercentageAttribute()
    {
        return 100.00 - $this->cash_received_in_percentage;
    }

    public function getPaymentInCashAttribute()
    {
        if (userCompany()->isDiscountBeforeVAT()) {
            $price = $this->grandTotalPrice;
        }

        if (!userCompany()->isDiscountBeforeVAT()) {
            $price = $this->grandTotalPriceAfterDiscount;
        }

        if ($this->cash_received_in_percentage < 0) {
            return $price;
        }

        return $price * ($this->cash_received_in_percentage / 100);
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

    public function isPaymentCredit()
    {
        return $this->payment_type == 'Credit Payment';
    }
}
