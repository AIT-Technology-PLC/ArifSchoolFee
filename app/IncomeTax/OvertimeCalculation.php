<?php

namespace App\IncomeTax;

class OvertimeCalculation
{
    public static function ethiopia()
    {
        return [
            'day' => 1.25,
            'night' => 1.5,
            'week' => 2,
            'public' => 2.5,
        ];
    }

    public static function get($basicSalary, $hoursWorked, $period)
    {
        $hourlyRate = $basicSalary / userCompany()->working_days / 8;

        return $hourlyRate * static::ethiopia()[$period] * $hoursWorked;
    }
}
