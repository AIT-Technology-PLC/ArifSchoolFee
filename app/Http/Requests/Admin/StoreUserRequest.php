<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'permissions' => ['required', 'array'],
            'permissions.*' => ['required', 'string', Rule::exists(Permission::class, 'name')->where('name', 'LIKE', 'Manage Admin Panel%')->whereNot('name', 'Manage Admin Panel Users')],
        ];
    }
}
