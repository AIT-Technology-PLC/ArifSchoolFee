<?php

namespace App\Http\Requests;

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
            'code' => ['required', 'integer', new UniqueReferenceNum('expenses', $this->route('expense')->id), Rule::excludeIf(!userCompany()->isEditingReferenceNumberEnabled())],
            'tax_id' => ['required', 'integer', new MustBelongToCompany('taxes')],
            'supplier_id' => ['nullable', 'integer', new MustBelongToCompany('suppliers')],
            'contact_id' => ['nullable', 'integer', new MustBelongToCompany('contacts')],
            'issued_on' => ['required', 'date'],
            'reference_number' => ['nullable', 'integer'],
            'description' => ['nullable', 'string'],
            'expense' => ['required', 'array'],
            'expense.*.name' => ['required', 'string'],
            'expense.*.expense_category_id' => ['required', 'integer', new MustBelongToCompany('expense_categories')],
            'expense.*.quantity' => ['required', 'numeric', 'gt:0'],
            'expense.*.unit_price' => ['required', 'numeric'],
            'payment_type' => ['required', 'string'],
            'bank_name' => ['nullable', 'string', 'prohibited_if:payment_type,Cash Payment'],
            'bank_reference_number' => ['nullable', 'string', 'prohibited_if:payment_type,Cash Payment'],
        ];
    }
}