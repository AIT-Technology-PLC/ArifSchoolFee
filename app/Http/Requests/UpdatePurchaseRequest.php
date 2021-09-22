<?php

namespace App\Http\Requests;

use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', new UniqueReferenceNum('purchases', $this->route('purchase')->id)],
            'purchase' => ['required', 'array'],
            'type' => ['required', 'string'],
            'purchase.*.product_id' => ['required', 'integer'],
            'purchase.*.quantity' => ['required', 'numeric'],
            'purchase.*.unit_price' => ['required', 'numeric'],
            'supplier_id' => ['nullable', 'integer'],
            'purchased_on' => ['required', 'date'],
            'payment_type' => ['required', 'string'],
            'description' => ['nullable', 'string'],
        ];
    }
}
