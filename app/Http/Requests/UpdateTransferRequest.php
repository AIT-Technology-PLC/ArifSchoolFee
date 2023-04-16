<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Validation\Rule;
use App\Rules\CheckBatchQuantity;
use App\Rules\UniqueReferenceNum;
use App\Rules\MustBelongToCompany;
use App\Rules\CheckValidBatchNumber;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\BatchSelectionIsRequiredOrProhibited;

class UpdateTransferRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', new UniqueReferenceNum('transfers', $this->route('transfer')->id),
                Rule::excludeIf(!userCompany()->isEditingReferenceNumberEnabled())],
            'transfer' => ['required', 'array'],
            'transfer.*.product_id' => ['required', 'integer', Rule::in(Product::inventoryType()->pluck('id'))],
            'transfer.*.warehouse_id' => ['required', 'integer', 'same:transferred_from', 'exclude'],
            'transfer.*.quantity' => ['required', 'numeric', 'gt:0', new CheckBatchQuantity($this->input('transfer'))],
            'transfer.*.description' => ['nullable', 'string'],
            'transfer.*.merchandise_batch_id' => [
                new BatchSelectionIsRequiredOrProhibited, 
                Rule::forEach(fn($v,$a) => is_null($v) ? [] : ['integer', new MustBelongToCompany('merchandise_batches'), new CheckValidBatchNumber]),
            ],
            'transferred_from' => ['required', 'integer', new MustBelongToCompany('warehouses')],
            'transferred_to' => ['required', 'integer', 'different:transferred_from', Rule::in(authUser()->getAllowedWarehouses('add')->pluck('id'))],
            'issued_on' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }
}
