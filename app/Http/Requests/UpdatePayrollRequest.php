<?php

namespace App\Http\Requests;

use App\Models\Payroll;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePayrollRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'integer', new UniqueReferenceNum('payrolls', $this->route('payroll')->id)],
            'working_days' => ['nullable', 'numeric', 'min:1', 'max:31'],
            'issued_on' => ['required', 'date'],
            'starting_period' => ['required', 'date', function ($attribute, $value, $fail) {
                if (Payroll::where('ending_period', '>=', $value)->whereNot('id', $this->route('payroll')->id)->exists()) {
                    $fail('This starting period is already taken.');
                }
            }],
            'ending_period' => ['required', 'date', 'after:starting_period'],
        ];
    }
}
