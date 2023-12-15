<?php

namespace App\Rules;

use App\Models\Customer;
use App\Utilities\Price;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckCustomerDepositBalance implements ValidationRule
{
    private $discount;

    private $details;

    private $paymentType;

    private $message;

    public function __construct($discount, $details, $paymentType)
    {
        $this->discount = $discount;

        $this->details = $details;

        $this->paymentType = $paymentType;

        $this->message = 'This customer has not enough deposit balance.';
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->paymentType != 'Deposits' || is_null($value)) {
            return;
        }

        if (empty($this->details)) {
            $fail('Please provide all payment details information.');
        }

        $customer = Customer::find($value);

        if (userCompany()->isDiscountBeforeTax()) {
            $price = Price::getGrandTotalPrice($this->details);
        }

        if (!userCompany()->isDiscountBeforeTax()) {
            $price = Price::getGrandTotalPriceAfterDiscount($this->discount, $this->details);
        }

        if ($customer->balance < $price) {
            $fail($this->message);
        }
    }
}
