<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Validation\Rule;
use App\Rules\UniqueReferenceNum;
use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\BatchSelectionIsRequiredOrProhibited;

class UpdateGrnRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', new UniqueReferenceNum('grns', $this->route('grn')->id),
                Rule::excludeIf(!userCompany()->isEditingReferenceNumberEnabled())],
            'grn' => ['required', 'array'],
            'grn.*.product_id' => ['required', 'integer', Rule::in(Product::inventoryType()->pluck('id'))],
            'grn.*.warehouse_id' => ['required', 'integer', Rule::in(authUser()->getAllowedWarehouses('add')->pluck('id'))],
            'grn.*.quantity' => ['required', 'numeric', 'gt:0'],
            'grn.*.unit_cost' => ['nullable', 'numeric', 'min:0'],
            'grn.*.description' => ['nullable', 'string'],
            'grn.*.batch_no' => [new BatchSelectionIsRequiredOrProhibited(false), Rule::forEach(fn($v,$a) => is_null($v) ? [] : ['string'])],
            'grn.*.expires_on' => ['nullable', 'date'],
            'supplier_id' => ['nullable', 'integer', new MustBelongToCompany('suppliers')],
            'issued_on' => ['required', 'date', 'before_or_equal:now'],
            'description' => ['nullable', 'string'],
        ];
    }
}
