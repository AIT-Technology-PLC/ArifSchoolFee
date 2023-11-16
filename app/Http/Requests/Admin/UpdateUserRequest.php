<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

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
            'permissions' => ['sometimes', 'required', 'array'],
            'permissions.*' => ['sometimes', 'required', 'string', Rule::exists(Permission::class, 'name')->where('name', 'LIKE', 'Manage Admin Panel%')->whereNot('name', 'Manage Admin Panel Users')],
        ];
    }
}
