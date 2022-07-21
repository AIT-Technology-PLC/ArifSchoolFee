<?php

namespace App\Http\Requests;

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
            'employee_id' => ['required', 'integer', new MustBelongToCompany('employees')],
            'type' => ['required', 'string', 'max:255', Rule::in(['Initial Warning', 'Affirmation Warning', 'Final Warning'])],
            'issued_on' => ['required', 'date'],
            'letter' => ['required', 'string'],
        ];
    }
}
