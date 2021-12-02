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
            'type' => ['required', 'string'],
            'min_price' => [
                'nullable',
                'numeric',
                'required_if:type,range',
                'prohibited_if:type,fixed',
                'gt:0',
                'lt:max_price',
                'max:99999999999999999999.99',
            ],
            'max_price' => [
                'nullable',
                'numeric',
                'required_if:type,range',
                'prohibited_if:type,fixed',
                'gt:0',
                'gt:min_price',
                'max:99999999999999999999.99',
            ],
            'fixed_price' => [
                'nullable',
                'numeric',
                'required_if:type,fixed',
                'prohibited_if:type,range',
                'max:99999999999999999999.99',
            ],
        ];
    }
}
