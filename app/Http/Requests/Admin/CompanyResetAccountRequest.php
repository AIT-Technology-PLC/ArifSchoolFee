<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyResetAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reset_inventory' => ['nullable', 'boolean'],
            'reset_finance' => ['nullable', 'boolean'],
            'reset_pad' => ['nullable', 'boolean'],
            'tables' => ['nullable', 'array'],
            'tables.*' => ['string', Rule::in(['brands', 'product_categories', 'products', 'contacts', 'customers', 'suppliers', 'purchases', 'prices', 'price_increments'])],
        ];
    }
}
