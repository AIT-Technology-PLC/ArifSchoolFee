<?php

namespace App\Rules;

use App\Utilities\Price;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class VerifyCashReceivedAmountIsValid implements ValidationRule
{
    private $paymentType;

    private $discount;

    private $details;

    private $cashReceivedType;

    private $hasWithholding;

    public function __construct($paymentType, $discount, $details, $cashReceivedType, $hasWithholding = false)
    {
        $this->paymentType = $paymentType;

        $this->discount = $discount;

        $this->details = $details;

        $this->cashReceivedType = $cashReceivedType;

        $this->hasWithholding = $hasWithholding;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($this->details)) {
            $fail('Please provide all payment details information.');
        }

        if (userCompany()->isDiscountBeforeTax()) {
            $price = Price::getGrandTotalPrice($this->details);
        }

        if (!userCompany()->isDiscountBeforeTax()) {
            $price = Price::getGrandTotalPriceAfterDiscount($this->discount, $this->details);
        }

        if ($this->cashReceivedType == 'percent' && $value > 100) {
            $fail('When type is "Percent", the percentage amount must be between 0 and 100.');
        }

        if ($this->paymentType != 'Credit Payment' && $this->cashReceivedType == 'amount' && $price != $value) {
            $fail('"Paid Amount" must be equal to the "Grand Total Price"');
        }

        if ($this->paymentType != 'Credit Payment' && $this->cashReceivedType == 'percent' && $value != 100) {
            $fail('When payment type is not "Credit Payment" and type is "Percent", the percentage must be 100');
        }

        if ($this->paymentType == 'Credit Payment' && $this->cashReceivedType == 'amount' && $value >= $price) {
            $fail('"Advanced Payment" can not be greater than or equal to "Grand Total Price"');
        }

        if ($this->paymentType == 'Credit Payment' && $this->cashReceivedType == 'percent' && $value == 100) {
            $fail('When payment type is "Credit Payment" and type is "Percent", the percentage can not be 100');
        }

        if ($this->hasWithholding && $this->paymentType == 'Credit Payment' && $this->cashReceivedType == 'amount' && (Price::getTotalWithheldAmount($this->details) + $value) >= $price) {
            $fail('"Advanced Payment" plus withheld amount can not be greater than or equal to "Grand Total Price"');
        }

        if ($this->hasWithholding && $this->paymentType == 'Credit Payment' && $this->cashReceivedType == 'percent' && ((userCompany()->withholdingTaxes['tax_rate'] * 100) + $value) >= 100) {
            $fail('When payment type is "Credit Payment" and type is "Percent", the percentage plus the withholding percent can not be 100');
        }
    }
}
