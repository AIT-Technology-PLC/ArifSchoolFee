<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGrnRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', new UniqueReferenceNum('grns', $this->route('grn')->id)],
            'grn' => ['required', 'array'],
            'grn.*.product_id' => ['required', 'integer', new MustBelongToCompany('products')],
            'grn.*.warehouse_id' => ['required', 'integer', Rule::in(auth()->user()->getAllowedWarehouses('add')->pluck('id'))],
            'grn.*.quantity' => ['required', 'numeric', 'min:1'],
            'grn.*.description' => ['nullable', 'string'],
            'supplier_id' => ['nullable', 'integer', new MustBelongToCompany('suppliers')],
            'purchase_id' => ['nullable', 'integer', new MustBelongToCompany('purchases')],
            'issued_on' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }
}
