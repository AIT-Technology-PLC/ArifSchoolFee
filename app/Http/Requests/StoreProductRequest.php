<?php

namespace App\Http\Requests;

use App\Services\SetDataOwnerService;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
            'unit_of_measurement' => 'required|string|max:255',
            'min_on_hand' => 'required|numeric',
            'description' => 'nullable|string',
            'properties' => 'nullable|array',
            'product_category_id' => 'required|integer',
            'supplier_id' => 'nullable|integer',
        ];
    }

    public function passedValidation()
    {
        $this->merge(SetDataOwnerService::forNonTransaction());
    }
}
