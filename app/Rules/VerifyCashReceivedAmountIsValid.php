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

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($paymentType, $discount, $details, $cashReceivedType)
    {
        $this->paymentType = $paymentType;

        $this->discount = $discount;

        $this->details = $details;

        $this->cashReceivedType = $cashReceivedType;
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

        if (userCompany()->isDiscountBeforeVAT()) {
            $price = Price::getGrandTotalPrice($this->details);
        }

        if (!userCompany()->isDiscountBeforeVAT()) {
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
            $this->message = '"Cash Received" can not be greater than or equal to "Grand Total Price"';

            return false;
        }

        if ($this->paymentType == 'Credit Payment' && $this->cashReceivedType == 'percent' && $value == 100) {
            $this->message = 'When payment type is "Credit Payment" and type is "Percent", the percentage can not be 100';

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
