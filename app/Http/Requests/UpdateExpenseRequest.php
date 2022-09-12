<?php

namespace App\Http\Requests;

use App\Rules\CanEditReferenceNumber;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateExpenseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'integer', new UniqueReferenceNum('expenses', $this->route('expense')->id), new CanEditReferenceNumber('expenses')],
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
