<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Rules\UniqueReferenceNum;
use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'integer', new UniqueReferenceNum('expenses')],
            'tax_type' => ['nullable', 'string', Rule::in(['VAT', 'TOT', 'None'])],
            'supplier_id' => ['nullable', 'integer', new MustBelongToCompany('suppliers')],
            'issued_on' => ['required', 'date'],
            'expense' => ['required', 'array'],
            'expense.*.name' => ['required', 'string'],
            'expense.*.expense_category_id' => ['required', 'integer', new MustBelongToCompany('expense_categories')],
            'expense.*.quantity' => ['required', 'numeric', 'gt:0'],
            'expense.*.unit_price' => ['required', 'numeric'],
        ];
    }
}