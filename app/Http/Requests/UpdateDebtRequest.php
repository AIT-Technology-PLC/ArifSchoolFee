<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use App\Rules\ValidateCustomFields;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDebtRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'integer', new UniqueReferenceNum('debts', $this->route('debt')->id), Rule::excludeIf(!userCompany()->isEditingReferenceNumberEnabled())],
            'supplier_id' => ['required', 'integer', new MustBelongToCompany('suppliers')],
            'debt_amount' => ['required', 'numeric', 'gt:0'],
            'issued_on' => ['required', 'date', 'before_or_equal:now'],
            'due_date' => ['required', 'date', 'after:issued_on'],
            'description' => ['nullable', 'string'],
            'customField.*' => [new ValidateCustomFields('debt')],
        ];
    }
}
