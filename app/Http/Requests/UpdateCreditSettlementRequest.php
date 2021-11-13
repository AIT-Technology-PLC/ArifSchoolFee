<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCreditSettlementRequest extends FormRequest
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
            'bank_name' => ['nullable', 'string', 'required_unless:method,Cash', 'prohibited_if:method,Cash'],
            'reference_number' => ['nullable', 'string', 'required_unless:method,Cash', 'prohibited_if:method,Cash'],
            'settled_at' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }
}
