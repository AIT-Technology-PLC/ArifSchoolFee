<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'section' => ['required', 'array'],
            'section.*.name' => ['required', 'string', 'max:10', 'distinct', Rule::unique('sections')->where('company_id', userCompany()->id)->withoutTrashed()],
        ];
    }
}
