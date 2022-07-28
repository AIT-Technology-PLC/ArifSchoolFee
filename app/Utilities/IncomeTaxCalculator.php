<?php

namespace App\Utilities;

class IncomeTaxCalculator
{
    public static function calculate($taxableAmount)
    {
        $class = str(userCompany()->income_tax_region)->ucfirst()->prepend('App\\IncomeTax\\')->append('IncomeTaxCalculator')->toString();

        return $class::calculate($taxableAmount);
    }
}
