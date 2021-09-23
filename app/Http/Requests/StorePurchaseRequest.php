<?php

namespace App\Http\Requests;

use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', new UniqueReferenceNum('purchases')],
            'type' => ['required', 'string'],
            'purchase' => ['required', 'array'],
            'purchase.*.product_id' => ['required', 'integer'],
            'purchase.*.quantity' => ['required', 'numeric'],
            'purchase.*.unit_price' => ['required', 'numeric'],
            'purchase.*.discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'supplier_id' => ['nullable', 'integer'],
            'purchased_on' => ['required', 'date'],
            'payment_type' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }
}
