<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class VerifyCashReceivedAmountIsValid implements Rule
{
    private $discount;

    private $details;

    private $cashReceivedType;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($discount, $details, $cashReceivedType)
    {
        $this->discount = $discount;

        $this->details = $details;

        $this->cashReceivedType = $cashReceivedType;

        $this->totalPrice();
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */

    public function passes($attribute, $value)
    {
        if (userCompany()->isDiscountBeforeVAT()) {
            $price = $this->grandTotalPrice();
        }

        if (!userCompany()->isDiscountBeforeVAT()) {
            $price = $this->grandTotalPriceAfterDiscount();
        }

        if ($this->cashReceivedType == 'amount') {
            return $price >= $value;
        }

        return true;
    }
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '"Cash Received" can not be greater than "Grand Total Price"';
    }

    private function totalPrice()
    {
        foreach ($this->details as &$detail) {
            $detail['unit_price'] = userCompany()->isPriceBeforeVAT() ? $detail['unit_price'] : $detail['unit_price'] / 1.15;
            $totalPrice = number_format($detail['unit_price'] * $detail['quantity'], 2, thousands_separator:'');
            $discountAmount = 0.00;

            if (userCompany()->isDiscountBeforeVAT()) {
                $discount = ($detail['discount'] ?? 0.00) / 100;
                $discountAmount = number_format($totalPrice * $discount, 2, thousands_separator:'');
            }

            $detail['total_price'] = number_format($totalPrice - $discountAmount, 2, thousands_separator:'');
        }
    }

    private function subTotalPrice()
    {
        return number_format(
            collect($this->details)->sum('total_price'),
            2,
            thousands_separator:''
        );
    }

    private function vat()
    {
        return number_format(
            $this->subTotalPrice() * 0.15,
            2,
            thousands_separator:''
        );
    }

    private function grandTotalPrice()
    {
        return number_format(
            $this->subTotalPrice() + $this->vat(),
            2,
            thousands_separator:''
        );
    }

    private function grandTotalPriceAfterDiscount()
    {
        $discount = ($this->discount ?? 0.00) / 100;
        $discountAmount = number_format($this->grandTotalPrice() * $discount, 2, thousands_separator:'');

        return number_format(
            $this->grandTotalPrice() - $discountAmount,
            2,
            thousands_separator:''
        );
    }

}
