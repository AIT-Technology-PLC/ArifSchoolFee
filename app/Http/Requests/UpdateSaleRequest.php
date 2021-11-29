<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use App\Rules\ValidatePrice;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSaleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', new UniqueReferenceNum('sales', $this->route('sale')->id)],
            'sale' => ['required', 'array'],
            'sale.*.product_id' => ['required', 'integer', new MustBelongToCompany('products')],
            'sale.*.quantity' => ['required', 'numeric', 'min:1'],
            'sale.*.unit_price' => ['required', 'numeric', new ValidatePrice],
            'customer_id' => ['nullable', 'integer', new MustBelongToCompany('customers')],
            'sold_on' => ['required', 'date'],
            'payment_type' => ['required', 'string'],
            'description' => ['nullable', 'string'],
        ];
    }
}
