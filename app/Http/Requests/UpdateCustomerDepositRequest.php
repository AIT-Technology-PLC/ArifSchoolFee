<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerDepositRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'customer_id' => ['required', 'integer', new MustBelongToCompany('customers')],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'issued_on' => ['required', 'date', 'before_or_equal:now'],
            'deposited_at' => ['required', 'date', 'before_or_equal:issued_on'],
            'attachment' => ['sometimes', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5000'],
        ];
    }
}
