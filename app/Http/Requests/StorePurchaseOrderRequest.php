<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseOrderRequest extends FormRequest
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
            'purchaseOrder.*.product_id' => ['required', 'integer'],
            'purchaseOrder.*.quantity' => ['required', 'numeric', 'min:1'],
            'purchaseOrder.*.quantity_left' => ['required', 'numeric', 'min:1'],
            'purchaseOrder.*.unit_price' => ['required', 'numeric'],
            'purchaseOrder.*.description' => ['nullable', 'string'],
            'customer_id' => ['nullable', 'integer'],
            'received_on' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function prepareForValidation()
    {
        $purchaseOrders = $this->purchaseOrder;

        foreach ($purchaseOrders as &$purchaseOrder) {
            $purchaseOrder['quantity_left'] = $purchaseOrder['quantity'];
        }

        $this->merge([
            'purchaseOrder' => $purchaseOrders,
        ]);

    }

    public function passedValidation()
    {
        $this->merge([
            'is_closed' => 0,
        ]);
    }
}
