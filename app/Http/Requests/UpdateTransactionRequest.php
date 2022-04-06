<?php

namespace App\Http\Requests;

use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
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
            'transaction' => ['required', 'array'],
            'transaction.*.pad_field_id' => ['required', 'integer'],
            'transaction.*.value' => ['required', 'string'],
        ];
    }
}
