<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\Rules\CanEditReferenceNumber;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCostUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'code' => ['required', 'string', new UniqueReferenceNum('cost_updates', $this->route('cost_update')->id), new CanEditReferenceNumber('cost_updates')],
            'costUpdate' => ['required', 'array'],
            'costUpdate.*.product_id' => ['required', 'integer', Rule::in(Product::inventoryType()->pluck('id')), function ($a, $value, $fail) {
                $product = Product::find($value);

                if (!$product->hasQuantity()) {
                    $fail('Products that have no quantity can not have cost.');
                }

                if ($product->hasCost()) {
                    $fail('This product has cost histories which can not be overridden.');
                }
            }],
            'costUpdate.*.average_unit_cost' => ['required', 'numeric', 'gt:0'],
            'costUpdate.*.lifo_unit_cost' => ['nullable', 'numeric', 'gt:0'],
            'costUpdate.*.fifo_unit_cost' => ['nullable', 'numeric', 'gt:0'],
        ];
    }
}
