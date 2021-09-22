<?php

namespace App\Http\Requests;

use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;

class StoreReturnRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', new UniqueReferenceNum('returns')],
            'return' => ['required', 'array'],
            'return.*.product_id' => ['required', 'integer'],
            'return.*.warehouse_id' => ['required', 'integer'],
            'return.*.unit_price' => ['nullable', 'numeric'],
            'return.*.quantity' => ['required', 'numeric', 'min:1'],
            'return.*.description' => ['nullable', 'string'],
            'customer_id' => ['nullable', 'integer'],
            'issued_on' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }
}
