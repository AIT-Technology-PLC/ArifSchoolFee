<?php

namespace App\Http\Requests;

use App\Models\Compensation;
use App\Models\Employee;
use App\Rules\MustBelongToCompany;
use App\Rules\ValidateCompensationAmountIsValid;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdvancementRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'issued_on' => ['required', 'date', 'before_or_equal:now'],
            'type' => ['required', 'string', 'max:255', Rule::in(['Promotion', 'Demotion'])],
            'description' => ['nullable', 'string'],
            'advancement' => ['required', 'array'],
            'advancement.*.employee_id' => ['required', 'integer', new MustBelongToCompany('employees'), Rule::in(Employee::getEmployees(false)->pluck('id'))],
            'advancement.*.job_position' => ['required', 'string'],
            'advancement.*.compensation_id' => ['required', 'string', Rule::in(Compensation::active()->canBeInputtedManually()->earnings()->pluck('id'))],
            'advancement.*.amount' => ['required', 'numeric', new ValidateCompensationAmountIsValid],
        ];
    }
}
