<?php

namespace App\Rules;

use App\Models\Customer;
use App\Utilities\Price;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckCustomerCreditLimit implements ValidationRule
{
    private $discount;

    private $details;

    private $paymentType;

    private $cashReceivedType;

    private $cashReceived;

    private $message;

    public function __construct($discount, $details, $paymentType, $cashReceivedType, $cashReceived)
    {
        $this->discount = $discount;

        $this->details = $details;

        $this->paymentType = $paymentType;

        $this->cashReceivedType = $cashReceivedType;

        $this->cashReceived = $cashReceived;

        $this->message = 'The customer has exceeded the credit amount limit.';
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->paymentType != 'Credit Payment' || is_null($value)) {
            return;
        }

        if (empty($this->details) || is_null($this->cashReceivedType) || is_null($this->cashReceived)) {
            $fail('Please provide all payment details information.');
        }

        $customer = Customer::find($value);

        if (is_null($customer) || $customer->credit_amount_limit == 0.00) {
            return;
        }

        $totalCreditAmountProvided = $customer->credits()->sum('credit_amount');

        $currentCreditBalance = $totalCreditAmountProvided - $customer->credits()->sum('credit_amount_settled');

        $currentCreditLimit = $customer->credit_amount_limit - $currentCreditBalance;

        if (userCompany()->isDiscountBeforeTax()) {
            $price = Price::getGrandTotalPrice($this->details);
        }

        if (!userCompany()->isDiscountBeforeTax()) {
            $price = Price::getGrandTotalPriceAfterDiscount($this->discount, $this->details);
        }

        if ($this->cashReceivedType == 'amount') {
            $creditAmount = $price - $this->cashReceived;
        }

        if ($this->cashReceivedType == 'percent') {
            $cashReceived = $this->cashReceived / 100;
            $creditAmount = $price - ($price * $cashReceived);
        }

        if ($currentCreditLimit < $creditAmount) {
            $fail($this->message);
        }
    }
}
