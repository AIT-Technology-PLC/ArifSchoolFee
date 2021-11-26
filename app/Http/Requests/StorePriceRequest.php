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
            'product_id' => ['required', 'integer', new MustBelongToCompany('products')],
            'type' => ['required', 'string'],
            'min_price' => ['nullable', 'numeric', 'required_if:type,range', 'prohibited_if:type,fixed', 'gt:0', 'lt:max_price'],
            'max_price' => ['nullable', 'numeric', 'required_if:type,range', 'prohibited_if:type,fixed', 'gt:0', 'gt:min_price'],
            'fixed_price' => ['nullable', 'numeric', 'required_if:type,fixed', 'prohibited_if:type,range'],
        ];
    }
}
