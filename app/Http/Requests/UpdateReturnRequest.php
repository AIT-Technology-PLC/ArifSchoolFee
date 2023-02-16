<?php

namespace App\Http\Requests;

use App\Rules\CheckBatchQuantity;
use App\Rules\CheckValidBatchNumber;
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
            'code' => ['required', 'string', new UniqueReferenceNum('returns', $this->route('return')->id),
                Rule::excludeIf(!userCompany()->isEditingReferenceNumberEnabled())],
            'return' => ['required', 'array'],
            'return.*.product_id' => ['required', 'integer', new MustBelongToCompany('products')],
            'return.*.warehouse_id' => ['required', 'integer', Rule::in(authUser()->getAllowedWarehouses('add')->pluck('id'))],
            'return.*.unit_price' => ['nullable', 'numeric'],
            'return.*.quantity' => ['required', 'numeric', 'gt:0', new CheckBatchQuantity],
            'return.*.description' => ['nullable', 'string'],
            'return.*.merchandise_batch_id' => ['nullable', 'integer', new MustBelongToCompany('merchandise_batches'), new CheckValidBatchNumber],
            'customer_id' => ['nullable', 'integer', new MustBelongToCompany('customers')],
            'issued_on' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }
}
