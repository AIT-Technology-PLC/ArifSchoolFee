<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeExpenseClaimRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'issued_on' => ['required', 'date', 'before_or_equal:now'],
            'description' => ['nullable', 'string'],
            'expenseClaim' => ['required', 'array'],
            'expenseClaim.*.item' => ['required', 'string', 'max:255'],
            'expenseClaim.*.price' => ['required', 'numeric', 'gt:0'],
        ];
    }
}
