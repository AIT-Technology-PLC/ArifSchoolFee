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
            'expense_category_id' => ['nullable', 'integer', new MustBelongToCompany('expense_categories')],
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
