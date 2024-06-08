<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use App\Rules\ValidatePriceBelowCost;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePriceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'price' => ['required', 'array'],
            'price.*.product_id' => ['required', 'integer', new MustBelongToCompany('products')],
            'price.*.fixed_price' => ['required', 'numeric', 'gt:0', 'max:99999999999999999999.99', new ValidatePriceBelowCost],
            'price.*.name' => ['nullable', 'string'],
            'price.*.is_active' => ['required', 'boolean'],
        ];
    }
}
