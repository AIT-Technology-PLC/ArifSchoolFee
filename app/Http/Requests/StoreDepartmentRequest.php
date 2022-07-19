<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDepartmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'department' => ['required', 'array'],
            'department.*.name' => ['required', 'string', 'distinct', 'max:255', Rule::unique('departments', 'name')->where(function ($query) {
                return $query->where('company_id', userCompany()->id);
            })],
            'department.*.description' => ['nullable', 'string'],
        ];
    }
}