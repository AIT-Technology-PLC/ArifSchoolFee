<?php

namespace App\Http\Requests;

use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;

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
            'damage.*.product_id' => ['required', 'integer'],
            'damage.*.warehouse_id' => ['required', 'integer'],
            'damage.*.quantity' => ['required', 'numeric', 'min:1'],
            'damage.*.description' => ['nullable', 'string'],
            'issued_on' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }
}
