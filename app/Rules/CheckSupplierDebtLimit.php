<?php

namespace App\Rules;

use App\Models\Supplier;
use App\Utilities\Price;
use Illuminate\Contracts\Validation\Rule;

class CheckSupplierDebtLimit implements Rule
{
    private $details;

    private $paymentType;

    private $cashPayedType;

    private $cashPayed;

    private $message;

    public function __construct($details, $paymentType, $cashPayedType, $cashPayed)
    {
        $this->details = $details;

        $this->paymentType = $paymentType;

        $this->cashPayedType = $cashPayedType;

        $this->cashPayed = $cashPayed;

        $this->message = 'You can not exceed debt amount limit provided by this company.';
    }

    public function passes($attribute, $value)
    {
        if ($this->paymentType != 'Debt Payment' || is_null($value)) {
            return true;
        }

        if (is_null($this->details) || is_null($this->cashPayedType) || is_null($this->cashPayed)) {
            $this->message = 'Please provide all payment details information.';
            return false;
        }

        $supplier = Supplier::find($value);

        if ($supplier->debt_amount_limit == 0.00) {
            return true;
        }

        $totalDebtAmountProvided = $supplier->debts()->sum('debt_amount');

        $currentDebtBalance = $totalDebtAmountProvided - $supplier->debts()->sum('debt_amount_settled');

        $currentDebtLimit = $supplier->debt_amount_limit - $currentDebtBalance;

        $price = Price::getGrandTotalPrice($this->details);

        if ($this->cashPayedType == 'amount') {
            $debtAmount = $price - $this->cashPayed;
        }

        if ($this->cashPayedType == 'percent') {
            $cashPayed = $this->cashPayed / 100;
            $debtAmount = $price - ($price * $cashPayed);
        }

        return $currentDebtLimit >= $debtAmount;
    }

    public function message()
    {
        return $this->message;
    }
}
