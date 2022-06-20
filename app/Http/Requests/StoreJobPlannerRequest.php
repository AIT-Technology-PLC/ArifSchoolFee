<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;

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
            'jobPlanner.*.product_id' => ['required', 'integer', new MustBelongToCompany('products')],
            'jobPlanner.*.warehouse_id' => ['required', 'integer', new MustBelongToCompany('warehouses')],
            'jobPlanner.*.bill_of_material_id' => ['required', 'integer', new MustBelongToCompany('bill_of_materials')],
            'jobPlanner.*.quantity' => ['required', 'numeric', 'gt:0'],
        ];
    }
}