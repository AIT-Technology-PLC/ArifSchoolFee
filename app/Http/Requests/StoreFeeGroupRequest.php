<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFeeGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
{
        return [
            'name' => ['required', 'string', 'max:50', Rule::unique('fee_groups')->where('company_id', userCompany()->id)->withoutTrashed()],
            'description' => ['nullable', 'string', 'max:50'],
        ];
    }
}
