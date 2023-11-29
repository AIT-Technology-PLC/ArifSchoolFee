<?php

namespace App\Http\Requests;

use App\Models\Product;
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
            'type' => ['required', 'string', 'max:255', Rule::in(['Finished Goods', 'Raw Material', 'Non-inventory Product', 'Services'])],
            'code' => ['nullable', 'string', 'max:255'],
            'unit_of_measurement_source' => ['required', 'string', 'exclude', Rule::in(['Standard', 'Custom'])],
            'unit_of_measurement' => ['required', 'string', 'max:255'],
            'min_on_hand' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'properties' => ['nullable', 'array'],
            'product_category_id' => ['required', 'integer', new MustBelongToCompany('product_categories')],
            'supplier_id' => ['nullable', 'integer', new MustBelongToCompany('suppliers')],
            'brand_id' => ['nullable', 'integer', new MustBelongToCompany('brands')],
            'tax_id' => ['required', 'integer', new MustBelongToCompany('taxes')],
            'is_batchable' => ['nullable', 'boolean', 'exclude_if:is_product_single,0'],
            'batch_priority' => ['nullable', 'string', Rule::in(['fifo', 'lifo']), 'required_if:is_batchable,1', 'prohibited_unless:is_batchable,1'],
            'is_active' => ['required', 'boolean'],
            'is_active_for_sale' => ['required', 'boolean'],
            'is_active_for_purchase' => ['required', 'boolean'],
            'is_active_for_job' => ['required', 'boolean'],
            'reorder_level' => ['nullable', 'array', 'exclude_if:is_product_single,0'],
            'reorder_level.*' => ['nullable', 'numeric', 'min:0'],
            'inventory_valuation_method' => ['required', 'string', Rule::in(['fifo', 'lifo', 'average'])],
            'is_product_single' => ['required', 'boolean'],
            'productBundle' => ['nullable', 'array', 'required_if:is_product_single,0', 'prohibited_if:is_product_single,1'],
            'productBundle.*.component_id' => ['nullable', 'integer', 'distinct', 'required_if:is_product_single,0', 'prohibited_if:is_product_single,1', Rule::in(Product::pluck('id'))],
            'productBundle.*.quantity' => ['nullable', 'numeric', 'gt:0', 'required_if:is_product_single,0', 'prohibited_if:is_product_single,1'],
            'profit_margin_type' => ['nullable', 'string', 'prohibited_if:profit_margin_amount,null', Rule::in(['amount', 'percent'])],
            'profit_margin_amount' => ['nullable', 'numeric', 'prohibited_if:profit_margin_type,null'],
        ];
    }
}
