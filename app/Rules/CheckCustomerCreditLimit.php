<?php

namespace App\Rules;

use App\Models\Customer;
use App\Utilities\Price;
use Illuminate\Contracts\Validation\Rule;

class CheckCustomerCreditLimit implements Rule
{
    private $discount;

    private $details;

    private $paymentType;

    private $cashReceivedType;

    private $cashReceived;

    private $message;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($discount, $details, $paymentType, $cashReceivedType, $cashReceived)
    {
        $this->discount = $discount;

        $this->details = $details;

        $this->paymentType = $paymentType;

        $this->cashReceivedType = $cashReceivedType;

        $this->cashReceived = $cashReceived;

        $this->message = 'The customer has exceeded the credit amount limit.';
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
        if (!empty($this->details) && !is_null($value) && ($this->paymentType == 'Customer Deposit' || $this->paymentType != 'Credit Payment')) {
            $customer = Customer::find($value);

            if (userCompany()->isDiscountBeforeTax()) {
                $price = Price::getGrandTotalPrice($this->details);
            }

            if (!userCompany()->isDiscountBeforeTax()) {
                $price = Price::getGrandTotalPriceAfterDiscount($this->discount, $this->details);
            }

        }

        if ($this->paymentType == 'Customer Deposit') {
            if (empty($this->details)) {
                $this->message = 'Please provide all payment details information.';
                return false;
            }

            $totalDepositedBalance = $customer->sum('balance');

            if ($price > $totalDepositedBalance) {
                $this->message = 'This customer has not enough deposit balance.';
                return false;
            }

        }

        if ($this->paymentType != 'Credit Payment' || is_null($value)) {
            return true;
        }

        if (empty($this->details) || is_null($this->cashReceivedType) || is_null($this->cashReceived)) {
            $this->message = 'Please provide all payment details information.';
            return false;
        }

        if ($customer->credit_amount_limit == 0.00) {
            return true;
        }

        $totalCreditAmountProvided = $customer->credits()->sum('credit_amount');

        $currentCreditBalance = $totalCreditAmountProvided - $customer->credits()->sum('credit_amount_settled');

        $currentCreditLimit = $customer->credit_amount_limit - $currentCreditBalance;

        if ($this->cashReceivedType == 'amount') {
            $creditAmount = $price - $this->cashReceived;
        }

        if ($this->cashReceivedType == 'percent') {
            $cashReceived = $this->cashReceived / 100;
            $creditAmount = $price - ($price * $cashReceived);
        }

        return $currentCreditLimit >= $creditAmount;
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