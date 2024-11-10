<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'schoolType' => ['required', 'array'],
            'schoolType.*.name' => ['required', 'max:15', 'string', 'unique:school_types', 'regex:/^[A-Za-z\s]+$/'],
        ];
    }

    public function messages()
    {
        return [
            'schoolType.*.name.regex' => 'Should contain only letters and spaces',
        ];
    }
}
