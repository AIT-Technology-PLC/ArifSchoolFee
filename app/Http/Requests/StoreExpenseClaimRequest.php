<?php

namespace App\Http\Requests;

use App\Models\Employee;
use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreExpenseClaimRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'issued_on' => ['required', 'date', 'before_or_equal:now'],
            'employee_id' => ['required', 'integer', new MustBelongToCompany('employees'), Rule::in(Employee::getEmployees()->pluck('id'))],
            'description' => ['nullable', 'string'],
            'expenseClaim' => ['required', 'array'],
            'expenseClaim.*.item' => ['required', 'string', 'max:255'],
            'expenseClaim.*.price' => ['required', 'numeric', 'gt:0'],
        ];
    }
}
