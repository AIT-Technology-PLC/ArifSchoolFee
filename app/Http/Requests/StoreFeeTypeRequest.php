<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MustBelongToCompany;
use Illuminate\Validation\Rule;

class StoreFeeTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fee_group_id' => ['required', 'integer', new MustBelongToCompany('fee_groups')],
            'name' => ['required', 'string', 'max:50', 
                Rule::unique('fee_types')
                ->where('fee_group_id', $this->input('fee_group_id'))
                ->where('company_id', userCompany()->id)
                ->withoutTrashed()],
            'description' => ['nullable', 'string', 'max:50'],
        ];
    }
}
