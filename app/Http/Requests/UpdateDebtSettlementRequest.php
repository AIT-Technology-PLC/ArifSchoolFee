<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDebtSettlementRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'amount' => ['required', 'numeric', 'gt:0'],
            'method' => ['required', 'string'],
            'bank_name' => ['nullable', 'string', 'required_unless:method,Cash'],
            'reference_number' => ['nullable', 'string', 'required_unless:method,Cash'],
            'settled_at' => ['required', 'date', 'after_or_equal:' . $this->route('debt_settlement')->debt->issued_on->toDateString()],
            'description' => ['nullable', 'string'],
        ];
    }
}
