<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MustBelongToCompany;
use Illuminate\Validation\Rule;

class StoreSchoolClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:10', Rule::unique('school_classes')->where('company_id', userCompany()->id)->withoutTrashed()],
            'section_id' => ['nullable', 'array'],
            'section_id.*' => ['required', 'integer', new MustBelongToCompany('sections')],
        ];
    }
}
