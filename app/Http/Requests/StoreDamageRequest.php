<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDamageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', new UniqueReferenceNum('damages')],
            'damage' => ['required', 'array'],
            'damage.*.product_id' => ['required', 'integer', new MustBelongToCompany('products')],
            'damage.*.warehouse_id' => ['required', 'integer', Rule::in(authUser()->getAllowedWarehouses('subtract')->pluck('id'))],
            'damage.*.quantity' => ['required', 'numeric', 'gt:0'],
            'damage.*.description' => ['nullable', 'string'],
            'issued_on' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }
}
