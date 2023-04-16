<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Validation\Rule;
use App\Rules\UniqueReferenceNum;
use App\Rules\MustBelongToCompany;
use App\Rules\CheckValidBatchNumber;
use App\Rules\CanEditReferenceNumber;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\BatchSelectionIsRequiredOrProhibited;

class StoreAdjustmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', new UniqueReferenceNum('adjustments'), new CanEditReferenceNumber('adjustments')],
            'adjustment' => ['required', 'array'],
            'adjustment.*.warehouse_id' => ['required', 'integer', Rule::in(authUser()->getAllowedWarehouses('adjustment')->pluck('id'))],
            'adjustment.*.product_id' => ['required', 'integer', Rule::in(Product::inventoryType()->pluck('id'))],
            'adjustment.*.is_subtract' => ['required', 'integer'],
            'adjustment.*.quantity' => ['required', 'numeric', 'gt:0'],
            'adjustment.*.reason' => ['required', 'string'],
            'adjustment.*.merchandise_batch_id' => [
                new BatchSelectionIsRequiredOrProhibited(false), 
                Rule::forEach(fn($v,$a) => is_null($v) ? [] : ['integer', new MustBelongToCompany('merchandise_batches'), new CheckValidBatchNumber]),
            ],
            'issued_on' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }
}
