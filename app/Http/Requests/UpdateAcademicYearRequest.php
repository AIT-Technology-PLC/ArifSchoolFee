<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAcademicYearRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'year' => ['required', 'integer','gt:0', 'distinct', Rule::unique('academic_years')->where('company_id', userCompany()->id)->where('id', '<>', $this->route('academic_year')->id)->withoutTrashed()],
            'title' => ['required', 'string', 'max:50'],
        ];
    }
}
