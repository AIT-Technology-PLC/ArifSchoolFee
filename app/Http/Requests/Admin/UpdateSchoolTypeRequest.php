<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSchoolTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'max:15', 'string', Rule::unique('school_types')->ignore($this->route('school_type')->id), 'regex:/^[A-Za-z\s]+$/'],
        ];
    }

    public function messages()
    {
        return [
            'name.regex' => 'Should contain only letters and spaces',
        ];
    }
}
