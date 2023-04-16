<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJobExtraRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'product_id' => ['required', 'integer', Rule::in(Product::inventoryType()->pluck('id'))],
            'quantity' => ['required', 'numeric', 'gt:0'],
            'type' => ['required', 'string', 'max:255', Rule::in(['Input', 'Remaining'])],
        ];
    }
}
