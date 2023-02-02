<?php

namespace App\Http\Requests;

use App\Rules\CheckBatchQuantity;
use App\Rules\CheckValidBatchNumber;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'transfer.*.product_id' => ['required', 'integer', new MustBelongToCompany('products')],
            'transfer.*.quantity' => ['required', 'numeric', 'gt:0', new CheckBatchQuantity],
            'transfer.*.description' => ['nullable', 'string'],
            'transfer.*.merchandise_batch_id' => [' nullable', 'integer', new MustBelongToCompany('merchandise_batches'), new CheckValidBatchNumber],
            'transferred_from' => ['required', 'integer', new MustBelongToCompany('warehouses')],
            'transferred_to' => ['required', 'integer', 'different:transferred_from', Rule::in(authUser()->getAllowedWarehouses('add')->pluck('id'))],
            'issued_on' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }
}
