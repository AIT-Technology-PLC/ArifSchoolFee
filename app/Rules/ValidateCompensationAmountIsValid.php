<?php

namespace App\Rules;

use App\Models\Compensation;
use Illuminate\Contracts\Validation\Rule;

class ValidateCompensationAmountIsValid implements Rule
{
    public function passes($attribute, $value)
    {
        $compensationIdKey = str_replace('.amount', '.compensation_id', $attribute);

        $compensation = Compensation::find(request()->input($compensationIdKey));

        if (is_null($compensation?->maximum_amount)) {
            return true;
        }

        return $compensation->maximum_amount >= $value;
    }

    public function message()
    {
        return 'Exceed the maximum amount of this compensation.';
    }
}
