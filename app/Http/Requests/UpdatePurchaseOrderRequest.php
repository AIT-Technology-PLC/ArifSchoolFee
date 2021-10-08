<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseOrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['nullable', 'string'],
            'purchaseOrder' => ['required', 'array'],
            'purchaseOrder.*.product_id' => ['required', 'integer', new MustBelongToCompany('products')],
            'purchaseOrder.*.quantity' => ['required', 'numeric', 'min:1'],
            'purchaseOrder.*.quantity_left' => ['required', 'numeric', 'lte:purchaseOrder.*.quantity'],
            'purchaseOrder.*.unit_price' => ['required', 'numeric'],
            'purchaseOrder.*.description' => ['nullable', 'string'],
            'customer_id' => ['nullable', 'integer', new MustBelongToCompany('customers')],
            'description' => ['nullable', 'string'],
        ];
    }
}
