<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJobExtraRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'jobExtra' => ['required', 'array'],
            'jobExtra.*.product_id' => ['required', 'integer', new MustBelongToCompany('products')],
            'jobExtra.*.quantity' => ['required', 'numeric', 'gt:0'],
            'jobExtra.*.type' => ['required', 'string', 'max:255', Rule::in(['Input', 'Remaining'])],
        ];
    }
}
