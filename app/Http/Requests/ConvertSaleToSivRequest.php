<?php

namespace App\Http\Requests;

use App\Rules\BatchSelectionIsRequiredOrProhibited;
use App\Rules\CheckValidBatchNumber;
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
        $saleDetails = $this->route('sale')->saleDetails;

        return [
            'sale' => ['sometimes', 'required', 'array'],
            'sale.*.product_id' => ['required_with:sale', 'integer', Rule::in($saleDetails->pluck('product_id'))],
            'sale.*.warehouse_id' => ['required_with:sale', 'integer', Rule::in($saleDetails->pluck('warehouse_id'))],
            'sale.*.quantity' => ['required_with:sale', 'numeric', 'min:0', new ValidateDeleveredQuantity($this->route('sale'))],
            'sale.*.merchandise_batch_id' => ['nullable',
                new BatchSelectionIsRequiredOrProhibited,
                Rule::forEach(fn($v, $a) => is_null($v) ? [] : ['integer', Rule::in($saleDetails->pluck('merchandise_batch_id')), new CheckValidBatchNumber]),
            ],
        ];
    }
}
