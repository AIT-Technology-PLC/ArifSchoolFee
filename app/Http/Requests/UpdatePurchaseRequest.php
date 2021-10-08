<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
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
            'purchase.*.product_id' => ['required', 'integer', new MustBelongToCompany('products')],
            'purchase.*.quantity' => ['required', 'numeric'],
            'purchase.*.unit_price' => ['required', 'numeric'],
            'purchase.*.discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'supplier_id' => ['nullable', 'integer', new MustBelongToCompany('suppliers')],
            'purchased_on' => ['required', 'date'],
            'payment_type' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }
}
