<?php

namespace App\Http\Requests;

use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', new UniqueReferenceNum('sales')],
            'sale' => ['required', 'array'],
            'sale.*.product_id' => ['required', 'integer'],
            'sale.*.quantity' => ['required', 'numeric', 'min:1'],
            'sale.*.unit_price' => ['required', 'numeric'],
            'customer_id' => ['nullable', 'integer'],
            'sold_on' => ['required', 'date'],
            'payment_type' => ['required', 'string'],
            'description' => ['nullable', 'string'],
        ];
    }
}
