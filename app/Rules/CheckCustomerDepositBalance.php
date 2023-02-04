<?php

namespace App\Rules;

use App\Models\Customer;
use App\Utilities\Price;
use Illuminate\Contracts\Validation\Rule;

class CheckCustomerDepositBalance implements Rule
{
    private $discount;

    private $details;

    private $paymentType;

    private $message;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($discount, $details, $paymentType)
    {
        $this->discount = $discount;

        $this->details = $details;

        $this->paymentType = $paymentType;

        $this->message = 'This customer has not enough deposit balance.';
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
        if ($this->paymentType != 'Deposits' || is_null($value)) {
            return true;
        }

        if (empty($this->details)) {
            $this->message = 'Please provide all payment details information.';
            return false;
        }

        $customer = Customer::find($value);

        if (userCompany()->isDiscountBeforeTax()) {
            $price = Price::getGrandTotalPrice($this->details);
        }

        if (!userCompany()->isDiscountBeforeTax()) {
            $price = Price::getGrandTotalPriceAfterDiscount($this->discount, $this->details);
        }

        return $customer->balance >= $price;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
