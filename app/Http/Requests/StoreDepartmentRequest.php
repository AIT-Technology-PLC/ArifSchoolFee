<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'department.*.name' => ['required', 'string', 'distinct', 'max:255', 'unique:companies'],
            'department.*.description' => ['nullable', 'string'],
        ];
    }
}