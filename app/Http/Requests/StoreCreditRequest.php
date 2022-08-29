<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;

class StoreCreditRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', new UniqueReferenceNum('credits'), function ($attribute, $value, $fail) {
                if ($this->get('code') != nextReferenceNumber('credits') && !userCompany()->isEditingReferenceNumberEnabled()) {
                    $fail('Modifying a reference number is not allowed.');
                }
            }],
            'customer_id' => ['required', 'integer', new MustBelongToCompany('customers')],
            'credit_amount' => ['required', 'numeric', 'gt:0'],
            'issued_on' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after:issued_on'],
            'description' => ['nullable', 'string'],
        ];
    }
}
