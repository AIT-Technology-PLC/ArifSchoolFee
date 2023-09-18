<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Rules\UniqueReferenceNum;
use App\Rules\MustBelongToCompany;
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
                Rule::excludeIf(!userCompany()->isEditingReferenceNumberEnabled())],
            'customer_id' => ['required', 'integer', new MustBelongToCompany('customers')],
            'credit_amount' => ['required', 'numeric', 'gt:0'],
            'issued_on' => ['required', 'date', 'before_or_equal:now'],
            'due_date' => ['nullable', 'date', 'after:issued_on'],
            'description' => ['nullable', 'string'],
        ];
    }
}
