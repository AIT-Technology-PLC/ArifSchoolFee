<?php

namespace App\Rules;

use App\Models\Employee;
use Illuminate\Contracts\Validation\Rule;

class ValidateTimeOffAmount implements Rule
{
    private $employeeId;

    private $isPaidTimeOff;

    public function __construct($employeeId = null, $isPaidTimeOff = null)
    {
        $this->employeeId = $employeeId;

        $this->isPaidTimeOff = $isPaidTimeOff;
    }

    public function passes($attribute, $value)
    {
        $employeeId = $this->employeeId;
        $isPaidTimeOff = $value;

        if (is_null($this->isPaidTimeOff)) {
            $isPaidTimeOffKey = str_replace('.time_off_amount', '.is_paid_time_off', $attribute);
            $isPaidTimeOff = request()->input($isPaidTimeOffKey);
        }

        if (!$isPaidTimeOff) {
            return true;
        }

        if (is_null($this->employeeId)) {
            $employeeIdKey = str_replace('.time_off_amount', '.employee_id', $attribute);
            $employeeId = request()->input($employeeIdKey);
        }

        $employee = Employee::firstWhere('id', $employeeId);

        return $employee->paid_time_off_amount >= $value;
    }

    public function message()
    {
        return "Employee has no enough Paid Time Off Amount.";
    }
}
