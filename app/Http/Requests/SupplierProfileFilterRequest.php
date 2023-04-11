<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupplierProfileFilterRequest extends FormRequest
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
            'period' => ['nullable', 'array'],
            'period.*' => ['nullable', 'date'],
            'supplier_id' => ['required', 'integer', new MustBelongToCompany('suppliers')],
            'tax_id' => ['nullable', 'integer', new MustBelongToCompany('taxes')],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'branches' => is_null($this->input('branches')) ? authUser()->getAllowedWarehouses('transactions')->pluck('id')->toArray() : [$this->input('branches')],
            'period' => is_null($this->input('period')) ? null : dateRangePicker($this->input('period')),
            'supplier_id' => $this->route('supplier')->id,
        ]);
    }
}