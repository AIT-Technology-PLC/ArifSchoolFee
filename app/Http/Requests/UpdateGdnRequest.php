<?php

namespace App\Http\Requests;

use App\Traits\PrependCompanyId;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGdnRequest extends FormRequest
{
    use PrependCompanyId;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', 'unique:gdns,code,' . $this->route('gdn')->id],
            'gdn' => ['required', 'array'],
            'gdn.*.product_id' => ['required', 'integer'],
            'gdn.*.warehouse_id' => ['required', 'integer'],
            'gdn.*.unit_price' => ['nullable', 'numeric'],
            'gdn.*.quantity' => ['required', 'numeric', 'min:1'],
            'gdn.*.description' => ['nullable', 'string'],
            'gdn.*.discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'customer_id' => ['nullable', 'integer'],
            'sale_id' => ['nullable', 'integer'],
            'issued_on' => ['required', 'date'],
            'payment_type' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'cash_received_in_percentage' => ['required', 'numeric', 'between:0,100'],
            'discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'code' => $this->prependCompanyId($this->code),
        ]);
    }
}
