<?php

namespace App\Http\Requests;

use App\Services\SetDataOwnerService;
use App\Traits\PrependCompanyId;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseRequest extends FormRequest
{
    use PrependCompanyId;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'purchase_no' => 'required|string|unique:purchases,purchase_no,' . $this->route('purchase')->id,
            'purchase' => 'required|array',
            'type' => 'required|string',
            'purchase.*.product_id' => 'required|integer',
            'purchase.*.quantity' => 'required|numeric',
            'purchase.*.unit_price' => 'required|numeric',
            'supplier_id' => 'nullable|integer',
            'purchased_on' => 'required|date',
            'payment_type' => 'required|string',
            'description' => 'nullable|string',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'code' => $this->prependCompanyId($this->purchase_no)
        ]);
    }

    public function passedValidation()
    {
        $this->merge(SetDataOwnerService::forUpdate());
    }
}
