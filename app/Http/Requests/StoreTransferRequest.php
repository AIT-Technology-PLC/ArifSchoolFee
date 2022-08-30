<?php

namespace App\Http\Requests;

use App\Rules\CanEditReferenceNumber;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTransferRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', new UniqueReferenceNum('transfers'), new CanEditReferenceNumber($this->get('code'), 'transfers')],
            'transfer' => ['required', 'array'],
            'transfer.*.product_id' => ['required', 'integer', new MustBelongToCompany('products')],
            'transfer.*.quantity' => ['required', 'numeric', 'gt:0'],
            'transfer.*.description' => ['nullable', 'string'],
            'transferred_from' => ['required', 'integer', new MustBelongToCompany('warehouses')],
            'transferred_to' => ['required', 'integer', 'different:transferred_from', Rule::in(authUser()->getAllowedWarehouses('add')->pluck('id'))],
            'issued_on' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }
}
