<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Plan;
use App\Models\SchoolType;

class StoreCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:60', 'unique:companies,name'],
            'email' => ['nullable', 'string', 'email', 'max:30', 'unique:companies'],
            'phone' => ['nullable', 'string', 'max:15', 'unique:companies'],
            'address' => ['nullable', 'string', 'max:50'],

            'name' => ['required', 'string', 'max:30', 'regex:/^[A-Za-z\s]+$/'],
            'user.email' => ['required', 'string', 'email', 'max:25', 'unique:users,email'],
            'user.phone' => ['required', 'string', 'max:15', 'unique:employees,phone'],
            'user.address' => ['nullable', 'string', 'max:25'],
            'gender' => ['required', 'string', 'max:6', Rule::in(['male', 'female'])],
            'password' => ['required', 'string', 'min:8', 'confirmed'],

            'school_type_id' => ['required', 'integer', Rule::in(SchoolType::pluck('id'))],
            'subscriptions.plan_id' => ['required', 'integer', Rule::in(Plan::enabled()->pluck('id'))],
            'limit.*.amount' => ['required', 'integer', 'gte:1'],
            'subscriptions.months' => ['required', 'integer', 'gte:1'],
        ];
    }

    public function messages()
    {
        return [
            'name.regex' => 'Should contain only letters and spaces',
        ];
    }
}
