<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAdvancementRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'issued_on' => ['required', 'date'],
            'type' => ['required', 'string', 'max:255', Rule::in(['Promotion', 'Demotion'])],
            'description' => ['nullable', 'string'],
            'advancement' => ['required', 'array'],
            'advancement.*.employee_id' => ['required', 'integer', 'distinct', new MustBelongToCompany('employees')],
            'advancement.*.gross_salary' => ['required', 'numeric'],
            'advancement.*.job_position' => ['required', 'string'],
        ];
    }
}
