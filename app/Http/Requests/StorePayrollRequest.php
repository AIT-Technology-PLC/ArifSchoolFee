<?php

namespace App\Http\Requests;

use App\Models\Payroll;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;

class StorePayrollRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'integer', new UniqueReferenceNum('payrolls')],
            'working_days' => ['nullable', 'numeric', 'min:1', 'max:31'],
            'issued_on' => ['required', 'date', 'before_or_equal:now'],
            'starting_period' => ['required', 'date', function ($attribute, $value, $fail) {
                if (Payroll::where('ending_period', '>=', $value)->exists()) {
                    $fail('This starting period is already taken.');
                }
            }],
            'ending_period' => ['required', 'date', 'after:starting_period'],
        ];
    }
}
