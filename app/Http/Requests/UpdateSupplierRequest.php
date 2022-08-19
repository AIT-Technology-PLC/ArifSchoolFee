<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSupplierRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'company_name' => ['required', 'string', 'max:255'],
            'tin' => ['nullable', 'numeric', 'digits:10', Rule::unique('suppliers')->where(function ($query) {
                return $query->where('company_id', userCompany()->id)->where('id', '<>', $this->route('supplier')->id);
            })],
            'address' => ['nullable', 'string', 'max:255'],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'debt_amount_limit' => ['required', 'numeric', 'min:0'],
        ];
    }
}
