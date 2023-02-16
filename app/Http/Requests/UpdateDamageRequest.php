<?php

namespace App\Http\Requests;

use App\Rules\CheckBatchQuantity;
use App\Rules\CheckValidBatchNumber;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDamageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', new UniqueReferenceNum('damages', $this->route('damage')->id),
                Rule::excludeIf(!userCompany()->isEditingReferenceNumberEnabled())],
            'damage' => ['required', 'array'],
            'damage.*.product_id' => ['required', 'integer', new MustBelongToCompany('products')],
            'damage.*.warehouse_id' => ['required', 'integer', Rule::in(authUser()->getAllowedWarehouses('subtract')->pluck('id'))],
            'damage.*.quantity' => ['required', 'numeric', 'gt:0', new CheckBatchQuantity],
            'damage.*.description' => ['nullable', 'string'],
            'damage.*.merchandise_batch_id' => ['nullable', 'integer', new MustBelongToCompany('merchandise_batches'), new CheckValidBatchNumber],
            'issued_on' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }
}
