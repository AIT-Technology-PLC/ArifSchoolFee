<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContactRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'Contact' => ['required', 'array'],
            'Contact.*.name' => ['required', 'string', 'max:255'],
            'Contact.*.tin' => ['nullable', 'numeric', 'digits:10', Rule::unique('contacts')->where('company_id', userCompany()->id)->withoutTrashed()],
            'Contact.*.email' => ['nullable', 'string', 'email', 'max:255'],
            'Contact.*.phone' => ['nullable', 'string', 'max:255'],
        ];
    }
}
