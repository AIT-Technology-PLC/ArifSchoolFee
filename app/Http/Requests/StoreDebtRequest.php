<?php

namespace App\Http\Requests;

use App\Rules\CanEditReferenceNumber;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use App\Rules\ValidateCustomFields;
use Illuminate\Foundation\Http\FormRequest;

class StoreDebtRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', new UniqueReferenceNum('debts'), new CanEditReferenceNumber('debts')],
            'supplier_id' => ['required', 'integer', new MustBelongToCompany('suppliers')],
            'debt_amount' => ['required', 'numeric', 'gt:0'],
            'issued_on' => ['required', 'date', 'before_or_equal:now'],
            'due_date' => ['required', 'date', 'after:issued_on'],
            'description' => ['nullable', 'string'],
            'customField.*' => [new ValidateCustomFields('debt')],
        ];
    }
}
