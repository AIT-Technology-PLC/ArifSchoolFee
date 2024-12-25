<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->route('user')->id)],
            'user_type' => ['required', 'string', 'in:admin,call_center,bank'],
            'bank_name' => ['nullable', 'string', 'max:255', 'required_if:user_type,bank'],
        ];
    }
}
