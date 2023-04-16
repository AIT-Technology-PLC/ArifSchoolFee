<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'product_id' => ['required', 'integer', Rule::in(Product::finishedGoods()->pluck('id'))],
            'customer_id' => ['nullable', 'integer', new MustBelongToCompany('customers')],
            'billOfMaterial' => ['required', 'array'],
            'billOfMaterial.*.product_id' => ['nullable', 'integer', 'different:product_id', 'distinct', Rule::in(Product::rawMaterial()->pluck('id'))],
            'billOfMaterial.*.quantity' => ['nullable', 'numeric', 'gt:0'],
        ];
    }
}
