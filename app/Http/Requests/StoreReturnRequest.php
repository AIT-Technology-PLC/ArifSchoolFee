<?php

namespace App\Http\Requests;

use App\Models\GdnDetail;
use App\Rules\CanEditReferenceNumber;
use App\Rules\CheckBatchQuantity;
use App\Rules\CheckValidBatchNumber;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use App\Rules\ValidateReturnQuantity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReturnRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', new UniqueReferenceNum('returns'), new CanEditReferenceNumber('returns')],
            'return' => ['required', 'array'],
            'return.*.product_id' => ['required', 'integer', new MustBelongToCompany('products'), function ($attribute, $value, $fail) {
                if (!GdnDetail::where('gdn_id', $this->get('gdn_id'))->where('product_id', $value)->exists()) {
                    $fail('This Product is not sold in the above DO!');
                }
            }],
            'return.*.warehouse_id' => ['required', 'integer', Rule::in(authUser()->getAllowedWarehouses('add')->pluck('id'))],
            'return.*.unit_price' => ['nullable', 'numeric'],
            'return.*.quantity' => ['required', 'numeric', 'gt:0', new CheckBatchQuantity, new ValidateReturnQuantity($this->get('gdn_id'))],
            'return.*.description' => ['nullable', 'string'],
            'return.*.merchandise_batch_id' => ['nullable', 'integer', new MustBelongToCompany('merchandise_batches'), new CheckValidBatchNumber],
            'gdn_id' => ['required', 'integer', new MustBelongToCompany('gdns')],
            'issued_on' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }
}
