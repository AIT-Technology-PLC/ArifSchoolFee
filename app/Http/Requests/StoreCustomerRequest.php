<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'company_name' => ['required', 'string', 'max:255'],
            'tin' => ['nullable', 'numeric', 'digits:10', Rule::unique('customers')->where('company_id', userCompany()->id)->withoutTrashed()],
            'address' => ['nullable', 'string', 'max:255'],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'credit_amount_limit' => ['required', 'numeric', 'min:0'],
            'business_licence' => ['sometimes', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5000'],
            'document_expire_on' => ['nullable', 'date'],
        ];
    }

    public function messages()
    {
        return [
            'business_licence.max' => 'The File must be less than 5 megabytes',
        ];
    }
}
