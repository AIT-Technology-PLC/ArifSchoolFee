<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'branches' => ['required', 'array'],
            'branches.*' => ['required', 'integer', Rule::in(authUser()->getAllowedWarehouses('transactions')->pluck('id'))],
            'period' => ['required', 'array'],
            'period.*' => ['required', 'date'],
            'user_id' => ['nullable', 'integer', new MustBelongToCompany('users')],
            'tax_id' => ['nullable', 'integer', new MustBelongToCompany('taxes')],
            'product_id' => ['nullable', 'integer', new MustBelongToCompany('products')],
            'bank_name' => ['nullable', 'string'],
            'expense_category_id' => ['nullable', 'integer', new MustBelongToCompany('expense_categories')],
            'source' => ['nullable', 'string', Rule::in(['Invoices', 'Delivery Orders'])],
            'customer_id' => ['nullable', 'integer', new MustBelongToCompany('customers')],
            'payment_method' => ['nullable', 'string', Rule::in(['Cash Payment', 'Credit Payment', 'Bank Deposit', 'Bank Transfer', 'Deposits', 'Cheque'])],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'branches' => is_null($this->input('branches')) ? authUser()->getAllowedWarehouses('transactions')->pluck('id')->toArray() : [$this->input('branches')],
            'period' => is_null($this->input('period')) ? [today(), today()] : dateRangePicker($this->input('period')),
        ]);
    }
}
