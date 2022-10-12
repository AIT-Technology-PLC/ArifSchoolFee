<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContactRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'tin' => ['nullable', 'numeric', 'digits:10', Rule::unique('contacts')->where('company_id', userCompany()->id)->where('id', '<>', $this->route('contact')->id)->withoutTrashed()],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
        ];
    }
}
