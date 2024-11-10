<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Plan;
use App\Rules\MustBelongToCompany;

class StoreCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:60'],
            'school_type_id' => ['required', 'integer'],
            'company_email' => ['nullable', 'string', 'email', 'max:30', 'unique:users'],
            'company_phone' => ['nullable', 'number', 'max:15'],
            'company_address' => ['nullable', 'string', 'max:50'],

            'name' => ['required', 'string', 'max:30', 'regex:/^[A-Za-z\s]+$/'],
            'gender' => ['required', 'string', 'max:6', Rule::in(['male', 'female'])],
            'phone' => ['required', 'string', 'max:15'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'address' => ['nullable', 'string', 'max:25'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],

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
