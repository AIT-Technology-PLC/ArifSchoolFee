<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCurrencyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'max:30', 'string'],
            'code' => ['required', 'max:6', 'string', 'unique:currencies'],
            'symbol' => ['required', 'max:5', 'string'],
            'exchange_rate' => ['nullable', 'numeric', 'gt:0'],
            'enabled' => ['required', 'boolean'],
        ];
    }
}
