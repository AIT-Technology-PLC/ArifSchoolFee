<?php

namespace App\Rules;

use App\Models\Compensation;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidateCompensationAmountIsValid implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $compensationIdKey = str_replace('.amount', '.compensation_id', $attribute);

        $compensation = Compensation::find(request()->input($compensationIdKey));

        if (is_null($compensation?->maximum_amount)) {
            return;
        }

        if ($compensation->maximum_amount < $value) {
            $fail('Exceed the maximum amount of this compensation.');
        }
    }
}
