<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;

class StoreBillOfMaterialRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'is_active' => ['required', 'boolean'],
            'name' => ['required', 'string'],
            'product_id' => ['required', 'integer', new MustBelongToCompany('products')],
            'customer_id' => ['nullable', 'integer', new MustBelongToCompany('customers')],
            'billOfMaterial' => ['required', 'array'],
            'billOfMaterial.*.product_id' => ['nullable', 'integer', 'different:product_id', 'distinct', new MustBelongToCompany('products')],
            'billOfMaterial.*.quantity' => ['nullable', 'numeric', 'gt:0'],
        ];
    }
}