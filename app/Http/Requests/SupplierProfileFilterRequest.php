<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;

class SupplierProfileFilterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'period' => ['nullable', 'array'],
            'period.*' => ['nullable', 'date'],
            'supplier_id' => ['required', 'integer', new MustBelongToCompany('suppliers')],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'period' => is_null($this->input('period')) ? null : dateRangePicker($this->input('period')),
            'supplier_id' => $this->route('supplier')->id,
        ]);
    }
}