<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255', Rule::in(['Finished Goods', 'Raw Material', 'Services'])],
            'code' => ['nullable', 'string', 'max:255'],
            'unit_of_measurement' => ['required', 'string', 'max:255'],
            'min_on_hand' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'properties' => ['nullable', 'array'],
            'product_category_id' => ['required', 'integer', new MustBelongToCompany('product_categories')],
            'supplier_id' => ['nullable', 'integer', new MustBelongToCompany('suppliers')],
            'brand_id' => ['nullable', 'integer', new MustBelongToCompany('brands')],
            'tax_id' => ['required', 'integer', new MustBelongToCompany('taxes')],
            'is_batchable' => ['nullable', 'boolean'],
            'batch_priority' => ['nullable', 'string', Rule::in(['fifo', 'lifo']), 'required_if:is_batchable,1', 'prohibited_unless:is_batchable,1'],
            'is_active' => ['required', 'boolean'],
            'is_active_for_sale' => ['required', 'boolean'],
            'is_active_for_purchase' => ['required', 'boolean'],
            'is_active_for_job' => ['required', 'boolean'],
            'reorder_level' => ['nullable', 'array'],
            'reorder_level.*' => ['nullable', 'numeric', 'min:0'],
            'inventory_valuation_method' => ['required', 'string', Rule::in(['fifo', 'lifo', 'average'])],
        ];
    }
}
