<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReturnRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', new UniqueReferenceNum('returns', $this->route('return')->id), function ($attribute, $value, $fail) {
                if ($this->get('code') != nextReferenceNumber('returns') && !userCompany()->isEditingReferenceNumberEnabled()) {
                    $fail('Modifying a reference number is not allowed.');
                }
            }],
            'return' => ['required', 'array'],
            'return.*.product_id' => ['required', 'integer', new MustBelongToCompany('products')],
            'return.*.warehouse_id' => ['required', 'integer', Rule::in(authUser()->getAllowedWarehouses('add')->pluck('id'))],
            'return.*.unit_price' => ['nullable', 'numeric'],
            'return.*.quantity' => ['required', 'numeric', 'gt:0'],
            'return.*.description' => ['nullable', 'string'],
            'customer_id' => ['nullable', 'integer', new MustBelongToCompany('customers')],
            'issued_on' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }
}
