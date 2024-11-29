<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MustBelongToCompany;
use Illuminate\Validation\Rule;

class UpdateFeeMasterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fee_type_id' => ['required', 'integer', new MustBelongToCompany('fee_types')],
            'due_date' => ['required', 'date', 'after:' . now()],
            'amount' => ['required', 'numeric', 'gte:0'],
            'fine_type' => ['nullable', 'string', 'max:20', 'required_unless:fine_amount,null', Rule::in(['percentage', 'amount'])],
            'fine_amount' => ['nullable', 'numeric', 'gte:0', 'required_unless:fine_type,null'],
       
        ];
    }
}
