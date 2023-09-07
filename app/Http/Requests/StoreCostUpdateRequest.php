<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\Rules\CanEditReferenceNumber;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCostUpdateRequest extends FormRequest
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
            'code' => ['required', 'string', new UniqueReferenceNum('cost_updates'), new CanEditReferenceNumber('cost_updates')],
            'costUpdate' => ['required', 'array'],
            'costUpdate.*.product_id' => ['required', 'integer', Rule::in(Product::inventoryType()->pluck('id'))],
            'costUpdate.*.average_unit_cost' => ['required', 'numeric', 'gt:0'],
            'costUpdate.*.lifo_unit_cost' => ['nullable', 'numeric', 'gt:0'],
            'costUpdate.*.fifo_unit_cost' => ['nullable', 'numeric', 'gt:0'],
        ];
    }
}
