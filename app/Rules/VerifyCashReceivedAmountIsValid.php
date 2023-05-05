<?php

namespace App\Rules;

use App\Utilities\Price;
use Illuminate\Contracts\Validation\Rule;

class VerifyCashReceivedAmountIsValid implements Rule
{
    private $paymentType;

    private $discount;

    private $details;

    private $cashReceivedType;

    private $message;

    private $hasWithholding;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($paymentType, $discount, $details, $cashReceivedType, $hasWithholding = false)
    {
        $this->paymentType = $paymentType;

        $this->discount = $discount;

        $this->details = $details;

        $this->cashReceivedType = $cashReceivedType;

        $this->hasWithholding = $hasWithholding;
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
        if (empty($this->details)) {
            return false;
        }

        if (userCompany()->isDiscountBeforeTax()) {
            $price = Price::getGrandTotalPrice($this->details);
        }

        if (!userCompany()->isDiscountBeforeTax()) {
            $price = Price::getGrandTotalPriceAfterDiscount($this->discount, $this->details);
        }

        if ($this->cashReceivedType == 'percent' && $value > 100) {
            $this->message = 'When type is "Percent", the percentage amount must be between 0 and 100.';

            return false;
        }

        if ($this->paymentType != 'Credit Payment' && $this->cashReceivedType == 'amount' && $price != $value) {
            $this->message = '"Paid Amount" must be equal to the "Grand Total Price"';

            return false;
        }

        if ($this->paymentType != 'Credit Payment' && $this->cashReceivedType == 'percent' && $value != 100) {
            $this->message = 'When payment type is not "Credit Payment" and type is "Percent", the percentage must be 100';

            return false;
        }

        if ($this->paymentType == 'Credit Payment' && $this->cashReceivedType == 'amount' && $value >= $price) {
            $this->message = '"Advanced Payment" can not be greater than or equal to "Grand Total Price"';

            return false;
        }

        if ($this->paymentType == 'Credit Payment' && $this->cashReceivedType == 'percent' && $value == 100) {
            $this->message = 'When payment type is "Credit Payment" and type is "Percent", the percentage can not be 100';

            return false;
        }

        if ($this->hasWithholding && $this->paymentType == 'Credit Payment' && $this->cashReceivedType == 'amount' && (Price::getTotalWithheldAmount($this->details) + $value) >= $price) {
            $this->message = '"Advanced Payment" plus withheld amount can not be greater than or equal to "Grand Total Price"';

            return false;
        }

        if ($this->hasWithholding && $this->paymentType == 'Credit Payment' && $this->cashReceivedType == 'percent' && ((userCompany()->withholdingTaxes['tax_rate'] * 100) + $value) >= 100) {
            $this->message = 'When payment type is "Credit Payment" and type is "Percent", the percentage plus the withholding percent can not be 100';

            return false;
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
        return $this->message;
    }
}
