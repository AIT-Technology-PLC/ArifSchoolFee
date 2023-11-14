<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\Rules\BatchSelectionIsRequiredOrProhibited;
use App\Rules\CanEditReferenceNumber;
use App\Rules\CheckBatchQuantity;
use App\Rules\CheckProductStatus;
use App\Rules\CheckValidBatchNumber;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use App\Rules\ValidateBackorder;
use App\Rules\ValidateCustomFields;
use App\Rules\ValidatePrice;
use App\Rules\ValidateReturnQuantity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreExchangeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'integer', new UniqueReferenceNum('exchanges'), new CanEditReferenceNumber('exchanges')],
            'gdn_id' => ['nullable', 'integer', 'prohibited_unless:sale_id,null', new MustBelongToCompany('gdns')],
            'sale_id' => ['nullable', 'integer', 'prohibited_unless:gdn_id,null', new MustBelongToCompany('sales')],
            'exchange' => ['required', 'array'],
            'exchange.*.product_id' => ['required', 'integer', Rule::in(Product::activeForSale()->pluck('id')), new ValidateBackorder(!is_null($this->input('gdn')) ? $this->input('gdn') : $this->input('sale')), new CheckProductStatus],
            'exchange.*.warehouse_id' => ['required', 'integer', Rule::in(authUser()->getAllowedWarehouses('sales')->pluck('id'))],
            'exchange.*.unit_price' => ['nullable', 'numeric', new ValidatePrice],
            'exchange.*.quantity' => ['required', 'numeric', 'gt:0', new CheckBatchQuantity($this->input('exchange'))],
            'exchange.*.returned_quantity' => ['required', 'numeric', 'min:0', new ValidateReturnQuantity($this->get('gdn_id'), $this->get('exchange'))],
            'exchange.*.merchandise_batch_id' => [
                new BatchSelectionIsRequiredOrProhibited,
                Rule::forEach(fn($v, $a) => is_null($v) ? [] : ['integer', new MustBelongToCompany('merchandise_batches'), new CheckValidBatchNumber]),
            ],
            'customField.*' => [new ValidateCustomFields('exchanges')],
        ];
    }
}
