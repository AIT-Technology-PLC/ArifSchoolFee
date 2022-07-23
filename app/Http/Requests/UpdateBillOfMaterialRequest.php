<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBillOfMaterialRequest extends FormRequest
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
            'billOfMaterial.*.product_id' => ['required', 'integer', 'different:product_id', 'distinct', new MustBelongToCompany('products')],
            'billOfMaterial.*.quantity' => ['required', 'numeric', 'gt:0'],
        ];
    }
}