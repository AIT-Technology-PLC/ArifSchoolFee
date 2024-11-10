<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50', Rule::unique('student_categories')->where('company_id', userCompany()->id)->where('id', '<>', $this->route('student_category')->id)->withoutTrashed()],
            'description' => ['nullable', 'string', 'max:100'],
        ];
    }
}
