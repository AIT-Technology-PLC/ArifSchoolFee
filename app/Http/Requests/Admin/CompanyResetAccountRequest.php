<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyResetAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reset_master' => ['nullable', 'boolean'],
            'reset_transaction' => ['nullable', 'boolean'],
            'tables' => ['nullable', 'array'],
            'tables.*' => ['string', Rule::in(['academic_years', 'sections', 'school_classes', 'routes', 'vehicles', 'fee_groups', 'fee_types', 'student_categories', 'student_groups','designations','departments'])],
        ];
    }
}
