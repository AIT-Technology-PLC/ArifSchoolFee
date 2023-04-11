<?php

namespace App\Http\Requests;

use App\Models\Gdn;
use App\Models\GdnDetail;
use App\Rules\CheckValidBatchNumber;
use App\Rules\MustBelongToCompany;
use App\Rules\BatchSelectionIsRequiredOrProhibited;
use App\Rules\UniqueReferenceNum;
use App\Rules\ValidateReturnQuantity;
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
            'return.*.product_id' => ['required', 'integer', new MustBelongToCompany('products'), function ($attribute, $value, $fail) {
                if ($this->get('gdn_id') &&  !GdnDetail::where('gdn_id', $this->get('gdn_id'))->where('product_id', $value)->exists()) {
                    $fail('This Product is not sold in the above DO!');
                }
            }],
            'return.*.warehouse_id' => ['required', 'integer', Rule::in(authUser()->getAllowedWarehouses('add')->pluck('id'))],
            'return.*.unit_price' => ['nullable', 'numeric'],
            'return.*.quantity' => ['required', 'numeric', 'min:0', new ValidateReturnQuantity($this->get('gdn_id'), $this->get('customer_id'), $this->get('return'))],
            'return.*.description' => ['nullable', 'string'],
            'return.*.merchandise_batch_id' => [
                new BatchSelectionIsRequiredOrProhibited(false), 
                Rule::forEach(fn($v,$a) => is_null($v) ? [] : ['integer', new MustBelongToCompany('merchandise_batches'), new CheckValidBatchNumber]),
            ],
            'gdn_id' => ['nullable', 'required_if:customer_id,null', 'integer', new MustBelongToCompany('gdns'), Rule::in(Gdn::getValidGdnsForReturn($this->get('gdn_id'))->flatten(1)->pluck('id'))],
            'customer_id' => ['nullable', 'required_if:gdn_id,null', 'integer', new MustBelongToCompany('customers'), Rule::in(Gdn::getValidGdnsForReturn($this->get('gdn_id'))->flatten(1)->pluck('customer_id'))],
            'issued_on' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }
}
