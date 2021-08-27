<?php

namespace App\Http\Requests;

use App\Services\SetDataOwnerService;
use App\Traits\PrependCompanyId;
use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
{
    use PrependCompanyId;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'receipt_no' => 'required|string|unique:sales',
            'sale' => 'required|array',
            'sale.*.product_id' => 'required|integer',
            'sale.*.quantity' => 'required|numeric|min:1',
            'sale.*.unit_price' => 'required|numeric',
            'customer_id' => 'nullable|integer',
            'sold_on' => 'required|date',
            'payment_type' => 'required|string',
            'description' => 'nullable|string',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'receipt_no' => $this->prependCompanyId($this->receipt_no),
        ]);
    }

    public function passedValidation()
    {
        $this->merge(SetDataOwnerService::forTransaction());
    }
}
