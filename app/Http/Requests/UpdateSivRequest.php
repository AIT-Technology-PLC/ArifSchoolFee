<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\Rules\BatchSelectionIsRequiredOrProhibited;
use App\Rules\CheckBatchQuantity;
use App\Rules\CheckValidBatchNumber;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use App\Rules\ValidateBackorder;
use App\Rules\ValidateCustomFields;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSivRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', new UniqueReferenceNum('sivs', $this->route('siv')->id),
                Rule::excludeIf(!userCompany()->isEditingReferenceNumberEnabled())],
            'purpose' => ['nullable', 'string'],
            'ref_num' => ['nullable', 'required_unless:purpose,null', 'prohibited_if:purpose,null', 'string'],
            'siv' => ['required', 'array'],
            'siv.*.product_id' => ['required', 'integer', Rule::in(Product::active()->inventoryType()->pluck('id')), Rule::when(userCompany()->canSivSubtract(), new ValidateBackorder($this->input('siv')))],
            'siv.*.warehouse_id' => ['required', 'integer', Rule::in(authUser()->getAllowedWarehouses('siv')->pluck('id'))],
            'siv.*.quantity' => ['required', 'numeric', 'gt:0', Rule::when(userCompany()->canSivSubtract(), new CheckBatchQuantity($this->input('siv')))],
            'siv.*.description' => ['nullable', 'string'],
            'siv.*.merchandise_batch_id' => [
                new BatchSelectionIsRequiredOrProhibited,
                Rule::forEach(fn($v, $a) => is_null($v) ? [] : ['integer', new MustBelongToCompany('merchandise_batches'), new CheckValidBatchNumber]),
            ],
            'issued_on' => ['required', 'date', 'before_or_equal:now'],
            'received_by' => ['nullable', 'string'],
            'delivered_by' => ['nullable', 'string'],
            'issued_to' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'customField.*' => [new ValidateCustomFields('siv')],
        ];
    }

    public function messages()
    {
        return [
            'ref_num.required_unless' => 'The Ref No is required for the purpose selected',
            'ref_num.prohibited_if' => 'The Ref No field requires one of the purposes to be selected',
        ];
    }
}
