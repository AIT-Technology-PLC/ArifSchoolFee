<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePriceIncrementRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', new UniqueReferenceNum('price_increments', $this->route('price_increment')->id)],
            'target_product' => ['required', 'string', Rule::In(['All Products', 'Specific Products', 'Upload Excel'])],
            'price_type' => ['required', 'string', Rule::In(['percent', 'amount'])],
            'price_increment' => ['required', 'numeric', 'gt:0', 'max:99999999999999999999.99'],
            'priceIncrement' => ['required', 'array'],
            'priceIncrement.*.product_id' => ['nullable', 'integer', new MustBelongToCompany('products')],
        ];
    }
}