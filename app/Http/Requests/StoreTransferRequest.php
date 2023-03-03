<?php

namespace App\Http\Requests;

use App\Rules\BatchSelectionIsRequiredOrProhibited;
use App\Rules\CanEditReferenceNumber;
use App\Rules\CheckBatchQuantity;
use App\Rules\CheckValidBatchNumber;
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
            'code' => ['required', 'string', new UniqueReferenceNum('transfers'), new CanEditReferenceNumber('transfers')],
            'transfer' => ['required', 'array'],
            'transfer.*.product_id' => ['required', 'integer', new MustBelongToCompany('products')],
            'transfer.*.warehouse_id' => ['required', 'integer', 'same:transferred_from', 'exclude'],
            'transfer.*.quantity' => ['required', 'numeric', 'gt:0', new CheckBatchQuantity($this->input('transfer'))],
            'transfer.*.description' => ['nullable', 'string'],
            'transfer.*.merchandise_batch_id' => ['nullable', 'integer', new BatchSelectionIsRequiredOrProhibited, new MustBelongToCompany('merchandise_batches'), new CheckValidBatchNumber],
            'transferred_from' => ['required', 'integer', new MustBelongToCompany('warehouses')],
            'transferred_to' => ['required', 'integer', 'different:transferred_from', Rule::in(authUser()->getAllowedWarehouses('add')->pluck('id'))],
            'issued_on' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }
}
