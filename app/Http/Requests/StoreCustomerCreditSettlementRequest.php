<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomerCreditSettlementRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'amount' => ['numeric', 'gt:0', Rule::when($this->route('customer')->credits()->sum('credit_amount') == $this->route('customer')->credits()->sum('credit_amount_settled'), 'prohibited', 'required')],
            'method' => ['required', 'string'],
            'bank_name' => ['nullable', 'string', 'required_unless:method,Cash', 'exclude_if:method,Cash'],
            'reference_number' => ['nullable', 'string', 'required_unless:method,Cash', 'exclude_if:method,Cash'],
            'settled_at' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }
}
