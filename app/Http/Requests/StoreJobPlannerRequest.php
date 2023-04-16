<?php

namespace App\Http\Requests;

use App\Models\BillOfMaterial;
use App\Models\Product;
use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJobPlannerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'jobPlanner' => ['required', 'array'],
            'jobPlanner.*.product_id' => ['required', 'integer', Rule::in(Product::activeForJob()->finishedGoods()->pluck('id'))],
            'jobPlanner.*.warehouse_id' => ['required', 'integer', new MustBelongToCompany('warehouses')],

            'jobPlanner.*.bill_of_material_id' => ['required', 'integer', new MustBelongToCompany('bill_of_materials'), function ($attribute, $value, $fail) {
                if (BillOfMaterial::where('id', $value)->where('product_id', $this->input(str_replace('.bill_of_material_id', '.product_id', $attribute)))->doesntExist()) {
                    $fail('Invalid bill of material!');
                }
            }],

            'jobPlanner.*.quantity' => ['required', 'numeric', 'gt:0'],
        ];
    }
}
