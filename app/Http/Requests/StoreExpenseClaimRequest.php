<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseClaimRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'issued_on' => ['required', 'date'],
            'employee_id' => ['required', 'integer', new MustBelongToCompany('employees')],
            'description' => ['nullable', 'string'],
            'expenseClaim' => ['required', 'array'],
            'expenseClaim.*.item' => ['required', 'string', 'max:255'],
            'expenseClaim.*.price' => ['required', 'numeric', 'gt:0'],
        ];
    }
}