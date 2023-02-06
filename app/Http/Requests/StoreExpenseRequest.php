<?php

namespace App\Http\Requests;

use App\Rules\CanEditReferenceNumber;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'integer', new UniqueReferenceNum('expenses'), new CanEditReferenceNumber('expenses')],
            'tax_id' => ['nullable', 'integer', new MustBelongToCompany('taxes')],
            'supplier_id' => ['nullable', 'integer', new MustBelongToCompany('suppliers')],
            'contact_id' => ['nullable', 'integer', new MustBelongToCompany('contacts')],
            'issued_on' => ['required', 'date'],
            'reference_number' => ['nullable', 'integer'],
            'expense' => ['required', 'array'],
            'expense.*.name' => ['required', 'string'],
            'expense.*.expense_category_id' => ['required', 'integer', new MustBelongToCompany('expense_categories')],
            'expense.*.quantity' => ['required', 'numeric', 'gt:0'],
            'expense.*.unit_price' => ['required', 'numeric'],
        ];
    }
}