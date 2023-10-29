<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\Rules\BatchSelectionIsRequiredOrProhibited;
use App\Rules\CheckValidBatchNumber;
use App\Rules\MustBelongToCompany;
use App\Rules\ValidateDeleveredQuantity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConvertSaleToSivRequest extends FormRequest
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
            'sale' => ['sometimes', 'required', 'array'],
            'sale.*.product_id' => ['required_with:sale', 'integer', Rule::in(Product::active()->inventoryType()->pluck('id'))],
            'sale.*.warehouse_id' => ['required_with:sale', 'integer', Rule::in(authUser()->getAllowedWarehouses('sale')->pluck('id'))],
            'sale.*.quantity' => ['required_with:sale', 'numeric', 'gt:0', new ValidateDeleveredQuantity($this->route('sale'))],
            'sale.*.merchandise_batch_id' => ['nullable',
                new BatchSelectionIsRequiredOrProhibited,
                Rule::forEach(fn($v, $a) => is_null($v) ? [] : ['integer', new MustBelongToCompany('merchandise_batches'), new CheckValidBatchNumber]),
            ],
        ];
    }
}
