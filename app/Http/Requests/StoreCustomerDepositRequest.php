<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerDepositRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'customerDeposit' => ['required', 'array'],
            'customerDeposit.*.customer_id' => ['required', 'integer', new MustBelongToCompany('customers')],
            'customerDeposit.*.bank_name' => ['nullable', 'string', 'max:255'],
            'customerDeposit.*.reference_number' => ['nullable', 'string', 'max:255'],
            'customerDeposit.*.amount' => ['required', 'numeric', 'gt:0'],
            'customerDeposit.*.issued_on' => ['required', 'date'],
            'customerDeposit.*.attachment' => ['sometimes', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4000'],
            'customerDeposit.*.deposited_at' => ['required', 'date', 'before_or_equal:customerDeposit.*.issued_on'],
        ];
    }
}
