<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;

class StorePriceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'price.*.product_id' => ['required', 'integer', 'distinct', 'unique:prices,product_id', new MustBelongToCompany('products')],
            'price.*.type' => ['required', 'string'],
            'price.*.min_price' => [
                'nullable',
                'numeric',
                'required_if:price.*.type,range',
                'prohibited_if:price.*.type,fixed',
                'gt:0',
                'lt:price.*.max_price',
                'max:99999999999999999999.99',
            ],
            'price.*.max_price' => [
                'nullable',
                'numeric',
                'required_if:price.*.type,range',
                'prohibited_if:price.*.type,fixed',
                'gt:0',
                'gt:price.*.min_price',
                'max:99999999999999999999.99',
            ],
            'price.*.fixed_price' => [
                'nullable',
                'numeric',
                'required_if:price.*.type,fixed',
                'prohibited_if:price.*.type,range',
                'gt:0',
                'max:99999999999999999999.99',
            ],
        ];
    }
}
