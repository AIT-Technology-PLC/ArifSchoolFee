<?php

namespace App\Rules;

use App\Models\Supplier;
use App\Utilities\Price;
use Illuminate\Contracts\Validation\Rule;

class CheckSupplierDebtLimit implements Rule
{
    private $details;

    private $paymentType;

    private $cashPaidType;

    private $cashPaid;

    private $message;

    public function __construct($details, $paymentType, $cashPaidType, $cashPaid)
    {
        $this->details = $details;

        $this->paymentType = $paymentType;

        $this->cashPaidType = $cashPaidType;

        $this->cashPaid = $cashPaid;

        $this->message = 'You can not exceed debt amount limit provided by this company.';
    }

    public function passes($attribute, $value)
    {
        if ($this->paymentType != 'Credit Payment' || is_null($value)) {
            return true;
        }

        if (is_null($this->details) || is_null($this->cashPaidType) || is_null($this->cashPaid)) {
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

        if ($this->cashPaidType == 'amount') {
            $debtAmount = $price - $this->cashPaid;
        }

        if ($this->cashPaidType == 'percent') {
            $cashPaid = $this->cashPaid / 100;
            $debtAmount = $price - ($price * $cashPaid);
        }

        return $currentDebtLimit >= $debtAmount;
    }

    public function message()
    {
        return $this->message;
    }
}
