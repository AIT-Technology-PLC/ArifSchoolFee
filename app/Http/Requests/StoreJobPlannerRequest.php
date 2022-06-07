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
            'Job Planner' => ['required', 'array'],
            'Job Planner.*.product_id' => ['required', 'integer', 'different:product_id', 'distinct', new MustBelongToCompany('products')],
            'Job Planner.*.bill_of_material_id' => ['required', 'integer', 'different:bill_of_material_id', 'distinct', new MustBelongToCompany('bill_of_materials')],
            'Job Planner.*.quantity' => ['required', 'numeric', 'gt:0'],
        ];
    }
}