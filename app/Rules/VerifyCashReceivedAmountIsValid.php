<?php

namespace App\Rules;

use App\Utilities\Price;
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
            $price = Price::getGrandTotalPrice($this->details);
        }

        if (!userCompany()->isDiscountBeforeVAT()) {
            $price = Price::getGrandTotalPriceAfterDiscount($this->discount, $this->details);
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
}