<?php

namespace App\Rules;

use App\Models\Employee;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;

class ValidateTimeOfAmount implements Rule
{
    private $message;

    public function __construct($details = [])
    {
        $this->details = $details;
    }

    public function passes($attribute, $value)
    {
        $employeeIdKey = str_replace('.time_off_amount', '.employee_id', $attribute);

        $employeeId = request()->input($employeeIdKey) ?? Arr::get($this->details, $employeeIdKey);

        $timeOfAmountKey = str_replace('.time_off_amount', '.is_paid_time_off', $attribute);

        $timeOfAmount = request()->input($timeOfAmountKey) ?? Arr::get($this->details, $timeOfAmountKey);

        $employee = Employee::firstWhere('id', $employeeId);

        if ($timeOfAmount == 1 && $employee->paid_time_off_amount < $value) {
            $this->message = "Employee has not enough Paid Time Off Amount .";

            return false;
        }

        return true;
    }

    public function message()
    {
        return $this->message;
    }
}