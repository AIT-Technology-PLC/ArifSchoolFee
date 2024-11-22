<?php

namespace App\Http\Requests\Admin;

use App\Models\Plan;
use App\Models\SchoolType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'plan_id' => ['required', 'integer', Rule::in(Plan::pluck('id'))],
            'school_type_id' => ['required', 'integer', Rule::in(SchoolType::pluck('id'))],
            'enabled_commission_setting' => ['nullable', 'boolean'],
            'charge_from' => ['nullable', 'string', 'max:5', Rule::in(['payer', 'payee'])],
            'charge_type' => ['nullable', 'string', 'max:7', Rule::in(['percent', 'amount'])],
            'charge_amount' => ['nullable', 'numeric', 'gte:0'],
        ];
    }
}
