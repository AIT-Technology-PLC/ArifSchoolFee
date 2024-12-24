<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Illuminate\Validation\Rule;

class StoreStudentPromoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id'=> ['required', 'array'],
            'student_id.*'=> ['required','integer', new MustBelongToCompany('students')],
            'warehouse_id' => ['required', 'integer', new MustBelongToCompany('warehouses')],
            'school_class_id' => ['required', 'integer', new MustBelongToCompany('school_classes')],
            'section_id' => ['required', 'integer', new MustBelongToCompany('sections')],
            'academic_year_id' => ['required', 'integer', new MustBelongToCompany('academic_years')],
        ];
    }
}
