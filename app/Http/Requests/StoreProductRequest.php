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
            'type' => ['required', 'string', 'max:255', Rule::when(userCompany()->plan->isPremium(), Rule::in(['Finished Goods', 'Raw Material', 'Services']), Rule::in(['Finished Goods', 'Services']))],
            'code' => ['nullable', 'string', 'max:255'],
            'unit_of_measurement' => ['required', 'string', 'max:255'],
            'min_on_hand' => ['required', 'numeric'],
            'description' => ['nullable', 'string'],
            'properties' => ['nullable', 'array'],
            'product_category_id' => ['required', 'integer', new MustBelongToCompany('product_categories')],
            'supplier_id' => ['nullable', 'integer', new MustBelongToCompany('suppliers')],
        ];
    }
}