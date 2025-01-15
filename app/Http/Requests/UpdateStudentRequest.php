<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', new UniqueReferenceNum('students', $this->route('student')->id)],
            'warehouse_id' => ['required', 'integer', new MustBelongToCompany('warehouses')],
            'school_class_id' => ['required', 'integer', new MustBelongToCompany('school_classes')],
            'section_id' => ['required', 'integer', new MustBelongToCompany('sections')],
            'student_category_id' => ['required', 'integer', new MustBelongToCompany('student_categories')],
            'student_group_id' => ['required', 'integer', new MustBelongToCompany('student_groups')],
            'academic_year_id' => ['required', 'integer', new MustBelongToCompany('academic_years')],
            'route_id' => ['nullable', 'integer', 'required_unless:vehicle_id,null', new MustBelongToCompany('routes')],
            'vehicle_id' => ['nullable', 'integer', 'required_unless:route_id,null', new MustBelongToCompany('vehicles')],
            'first_name' => ['required', 'string', 'max:15'],
            'last_name' => ['nullable', 'string', 'max:15'],
            'gender' => ['required', 'string', 'max:6', Rule::in(['male', 'female'])],
            'email' => ['required', 'string', 'email', 'max:30', Rule::unique('students')->ignore($this->route('student')->id)],
            'phone' => ['required', 'string', 'max:15', Rule::unique('students')->ignore($this->route('student')->id)],
            'date_of_birth' => ['nullable', 'date', 'before:' . now()],
            'admission_date' => ['nullable', 'date'],
            'father_name' => ['nullable', 'string', 'max:15', 'required_unless:father_phone,null'],
            'father_phone' => ['nullable', 'string', 'max:15', 'required_unless:father_name,null'],
            'mother_name' => ['nullable', 'string', 'max:15', 'required_unless:mother_phone,null'],
            'mother_phone' => ['nullable', 'string', 'max:15', 'required_unless:mother_name,null'],
            'current_address' => ['nullable', 'string', 'max:50'],
            'permanent_address' => ['nullable', 'string', 'max:50'],
        ];
    }
}
