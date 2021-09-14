<?php

namespace App\Http\Requests;

use App\Services\SetDataOwnerService;
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
            'purchaseOrder.*.product_id' => ['required', 'integer'],
            'purchaseOrder.*.quantity' => ['required', 'numeric', 'min:1'],
            'purchaseOrder.*.quantity_left' => ['required', 'numeric', 'lte:purchaseOrder.*.quantity'],
            'purchaseOrder.*.unit_price' => ['required', 'numeric'],
            'purchaseOrder.*.description' => ['nullable', 'string'],
            'customer_id' => ['nullable', 'integer'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function passedValidation()
    {
        $this->merge(SetDataOwnerService::forUpdate());
    }
}
