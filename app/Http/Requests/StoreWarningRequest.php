<?php

namespace App\Http\Requests;

use App\Models\Employee;
use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWarningRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'warning' => ['required', 'array'],
            'warning.*.employee_id' => ['required', 'integer', 'distinct', new MustBelongToCompany('employees'), Rule::notIn(Employee::getEmployees(false)->pluck('id'))],
            'warning.*.type' => ['required', 'string', 'max:255', Rule::in(['Initial Warning', 'Affirmation Warning', 'Final Warning'])],
            'warning.*.issued_on' => ['required', 'date'],
            'warning.*.letter' => ['required', 'string'],
        ];
    }
}
