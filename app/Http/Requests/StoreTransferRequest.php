<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransferRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', new UniqueReferenceNum('transfers')],
            'transfer' => ['required', 'array'],
            'transfer.*.product_id' => ['required', 'integer', new MustBelongToCompany('products')],
            'transfer.*.quantity' => ['required', 'numeric', 'min:1'],
            'transfer.*.description' => ['nullable', 'string'],
            'transferred_from' => ['required', 'integer', new MustBelongToCompany('warehouses')],
            'transferred_to' => ['required', 'integer', 'different:transferred_from', new MustBelongToCompany('warehouses')],
            'issued_on' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }
}
