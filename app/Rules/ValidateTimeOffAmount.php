<?php

namespace App\Rules;

use App\Models\Employee;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidateTimeOffAmount implements ValidationRule
{
    private $employeeId;

    private $isPaidTimeOff;

    public function __construct($employeeId = null, $isPaidTimeOff = null)
    {
        $this->employeeId = $employeeId;

        $this->isPaidTimeOff = $isPaidTimeOff;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $employeeId = $this->employeeId;
        $isPaidTimeOff = $value;

        if (is_null($this->isPaidTimeOff)) {
            $isPaidTimeOffKey = str_replace('.time_off_amount', '.is_paid_time_off', $attribute);
            $isPaidTimeOff = request()->input($isPaidTimeOffKey);
        }

        if (!$isPaidTimeOff) {
            return;
        }

        if (is_null($this->employeeId)) {
            $employeeIdKey = str_replace('.time_off_amount', '.employee_id', $attribute);
            $employeeId = request()->input($employeeIdKey);
        }

        $employee = Employee::firstWhere('id', $employeeId);

        if ($employee->paid_time_off_amount < $value) {
            $fail('Employee has no enough Paid Time Off Amount.');
        }
    }
}
