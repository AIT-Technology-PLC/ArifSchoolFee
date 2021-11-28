<?php

namespace App\Http\Requests;

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
            'price.*.product_id' => ['required', 'integer', new MustBelongToCompany('products')],
            'price.*.type' => ['required', 'string'],
            'price.*.min_price' => ['nullable', 'numeric', 'required_if:price.*.type,range', 'prohibited_if:price.*.type,fixed', 'gt:0', 'lt:price.*.max_price'],
            'price.*.max_price' => ['nullable', 'numeric', 'required_if:price.*.type,range', 'prohibited_if:price.*.type,fixed', 'gt:0', 'gt:price.*.min_price'],
            'price.*.fixed_price' => ['nullable', 'numeric', 'required_if:price.*.type,fixed', 'prohibited_if:price.*.type,range'],
        ];
    }
}
