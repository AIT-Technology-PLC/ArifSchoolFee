<?php

namespace App\Http\Requests;

use App\Rules\CanEditReferenceNumber;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCreditRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'integer', new UniqueReferenceNum('credits', $this->route('credit')->id),
                new CanEditReferenceNumber($this->get('code'), 'credits')],
            'customer_id' => ['required', 'integer', new MustBelongToCompany('customers')],
            'credit_amount' => ['required', 'numeric', 'gt:0'],
            'issued_on' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after:issued_on'],
            'description' => ['nullable', 'string'],
        ];
    }
}
