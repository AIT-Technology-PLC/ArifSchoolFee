<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCurrencyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'max:30', 'string'],
            'code' => ['required', 'max:5', 'string', Rule::unique('currencies')->ignore($this->route('currency')->id)],
            'symbol' => ['required', 'max:5', 'string'],
            'exchange_rate' => ['nullable', 'numeric', 'gt:0'],
            'enabled' => ['required', 'boolean'],
        ];
    }
}
