<?php

namespace App\Http\Requests\Admin;

use App\Models\Integration;
use App\Models\Plan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255'],
            'subscriptions.plan_id' => ['required', 'integer', Rule::in(Plan::enabled()->pluck('id'))],
            'subscriptions.months' => ['required', 'integer', 'gte:1'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'limit.*.amount' => ['required', 'integer'],
            'integrations.*' => ['nullable', 'integer', Rule::in(Integration::enabled()->pluck('id'))],
        ];
    }
}
