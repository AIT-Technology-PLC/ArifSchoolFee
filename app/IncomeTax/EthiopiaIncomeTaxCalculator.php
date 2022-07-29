<?php

namespace App\IncomeTax;

class EthiopiaIncomeTaxCalculator
{
    public static function calculate($taxableAmount)
    {
        if ($taxableAmount <= 600) {
            $taxAmount = 0;
        } elseif ($taxableAmount <= 1650) {
            $taxAmount = ($taxableAmount * 0.1) - 60;
        } elseif ($taxableAmount <= 3200) {
            $taxAmount = ($taxableAmount * 0.15) - 142.50;
        } elseif ($taxableAmount <= 5250) {
            $taxAmount = ($taxableAmount * 0.2) - 302.50;
        } elseif ($taxableAmount <= 7800) {
            $taxAmount = ($taxableAmount * 0.25) - 565;
        } elseif ($taxableAmount <= 10900) {
            $taxAmount = ($taxableAmount * 0.3) - 955;
        } else {
            $taxAmount = ($taxableAmount * 0.35) - 1500;
        }

        return [
            'taxable_amount' => $taxableAmount,
            'tax_amount' => $taxAmount,
            'income_after_tax' => $taxableAmount - $taxAmount,
        ];
    }
}