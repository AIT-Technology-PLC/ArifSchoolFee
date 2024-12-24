<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'account_type' => ['required', 'string', 'max:150', Rule::unique('accounts')->where('company_id', userCompany()->id)->where('id', '<>', $this->route('account')->id)->withoutTrashed()],
            'account_number' => ['required', 'string', 'max:15', Rule::unique('accounts')->where('company_id', userCompany()->id)->where('id', '<>', $this->route('account')->id)->withoutTrashed()],
            'account_holder' => ['required', 'string', 'max:50'],
            'is_active' => ['required', 'boolean'],
            'additional_info' => ['nullable', 'string', 'max:100'],
        ];
    }
}
