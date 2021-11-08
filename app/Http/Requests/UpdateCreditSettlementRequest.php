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
            'amount' => ['nullable', 'numeric', 'gt:0'],
            'method' => ['required', 'string'],
            'reference_number' => ['nullable', 'string'],
            'settled_at' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }
}
