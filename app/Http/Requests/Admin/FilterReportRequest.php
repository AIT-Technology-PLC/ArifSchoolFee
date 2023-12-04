<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subscription_period' => ['required', 'array'],
            'subscription_period.*' => ['required', 'date'],
            'transaction_period' => ['required', 'array'],
            'transaction_period.*' => ['required', 'date'],
            'company_id' => ['nullable', 'integer', Rule::exists('companies', 'id')],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'subscription_period' => is_null($this->input('subscription_period')) ? [today(), today()->addMonths(3)] : dateRangePicker($this->input('subscription_period')),
            'transaction_period' => is_null($this->input('transaction_period')) ? [today(), today()] : dateRangePicker($this->input('transaction_period')),
        ]);
    }
}
