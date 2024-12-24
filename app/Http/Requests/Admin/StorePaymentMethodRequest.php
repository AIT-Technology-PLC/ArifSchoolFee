<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentMethodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:30'],
            'type' => ['required', 'string', 'max:10'],
            'enabled' => ['required', 'boolean'],
        ];
    }
}
