<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCreditRequest extends FormRequest
{
    public function authorize()
    {
        return false;
    }

    public function rules()
    {
        return [
            'customer_id' => ['required', 'integer', new MustBelongToCompany('customers')],
            'credit_amount' => ['required', 'numeric'],
            'issued_on' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after:issued_on'],
            'description' => ['nullable', 'string'],
        ];
    }
}
