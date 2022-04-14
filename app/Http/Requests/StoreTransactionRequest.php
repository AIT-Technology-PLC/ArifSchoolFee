<?php

namespace App\Http\Requests;

use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'integer', new UniqueReferenceNum('transactions')],
            'issued_on' => ['required', 'date'],
            'master' => ['sometimes', 'required', 'array'],
            'detail' => ['sometimes', 'required', 'array'],
        ];
    }
}
