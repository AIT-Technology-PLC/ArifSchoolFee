<?php

namespace App\Utilities;

class IncomeTaxCalculator
{
    public static function calculate($taxableAmount)
    {
        $class = (string) str(userCompany()->income_tax_region)->ucfirst()->prepend('App\\IncomeTax\\')->append('IncomeTaxCalculator');

        return $class::calculate($taxableAmount);
    }
}