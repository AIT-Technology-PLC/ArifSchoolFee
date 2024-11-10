<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:30', 'regex:/^[A-Za-z\s]+$/', Rule::unique('roles')->ignore($this->route('role')->id)],
            'guard_name' => ['required', 'string', 'max:10', 'regex:/^[A-Za-z\s]+$/'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['nullable', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'name.regex' => 'Should contain only letters and spaces',
        ];
    }
}
