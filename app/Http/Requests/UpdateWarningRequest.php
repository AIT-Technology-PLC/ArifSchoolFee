<?php

namespace App\Http\Requests;

use App\Models\Employee;
use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWarningRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employee_id' => ['required', 'integer', new MustBelongToCompany('employees'), Rule::notIn(Employee::getEmployees(false)->pluck('id'))],
            'type' => ['required', 'string', 'max:255', Rule::in(['Initial Warning', 'Affirmation Warning', 'Final Warning'])],
            'issued_on' => ['required', 'date'],
            'letter' => ['required', 'string'],
        ];
    }
}
