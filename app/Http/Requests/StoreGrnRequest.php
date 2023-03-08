<?php

namespace App\Http\Requests;

use App\Rules\CanEditReferenceNumber;
use App\Rules\MustBelongToCompany;
use App\Rules\BatchSelectionIsRequiredOrProhibited;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGrnRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', new UniqueReferenceNum('grns'), new CanEditReferenceNumber('grns')],
            'grn' => ['required', 'array'],
            'grn.*.product_id' => ['required', 'integer', new MustBelongToCompany('products')],
            'grn.*.warehouse_id' => ['required', 'integer', Rule::in(authUser()->getAllowedWarehouses('add')->pluck('id'))],
            'grn.*.quantity' => ['required', 'numeric', 'gt:0'],
            'grn.*.unit_cost' => ['nullable', 'numeric', 'min:0'],
            'grn.*.description' => ['nullable', 'string'],
            'grn.*.batch_no' => ['string', new BatchSelectionIsRequiredOrProhibited(false)],
            'grn.*.expires_on' => ['nullable', 'date'],
            'supplier_id' => ['nullable', 'integer', new MustBelongToCompany('suppliers')],
            'issued_on' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }
}
