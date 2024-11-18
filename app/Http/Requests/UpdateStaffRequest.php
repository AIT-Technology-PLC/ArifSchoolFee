<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Illuminate\Validation\Rule;

class UpdateStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'integer', new UniqueReferenceNum('staff', $this->route('staff')->id)],
            'warehouse_id' => ['required', 'integer', new MustBelongToCompany('warehouses')],
            'department_id' => ['required', 'integer', new MustBelongToCompany('departments')],
            'designation_id' => ['required', 'integer', new MustBelongToCompany('designations')],
            'first_name' => ['required', 'string', 'max:15'],
            'last_name' => ['required', 'string', 'max:15'],
            'father_name' => ['nullable', 'string', 'max:15'],
            'mother_name' => ['nullable', 'string', 'max:15'],
            'email' => ['required', 'string', 'email', 'max:30', Rule::unique('staff')->ignore($this->route('staff')->id)],
            'phone' => ['required', 'string', 'max:15', Rule::unique('staff')->ignore($this->route('staff')->id)],
            'gender' => ['required', 'string', 'max:6', Rule::in(['male', 'female'])],
            'date_of_birth' => ['nullable', 'date', 'before:' . now()],
            'marital_status' => ['nullable', 'string', 'max:15', Rule::in(['married', 'single','divorced','widowed'])],
            'date_of_joining' => ['nullable', 'date', 'before:' . now()],
            'current_address' => ['required', 'string', 'max:50'],
            'permanent_address' => ['nullable', 'string', 'max:50'],
            'qualifications' => ['nullable', 'string', 'max:100'],
            'experience' => ['nullable', 'string', 'max:100'],
            'efp_number' => ['nullable', 'string', 'max:100'],
            'basic_salary' => ['required', 'numeric', 'gte:0'],
            'job_type' => ['required', 'string', 'max:20', Rule::in(['permanent', 'contract'])],
            'location' => ['nullable', 'string', 'max:50'],
            'bank_name' => ['nullable', 'string', 'max:25', 'required_unless:bank_account,null'],
            'bank_account' => ['nullable', 'string', 'max:30', 'required_unless:bank_name,null'],
            'branch_name' => ['nullable', 'string', 'max:30'],
            'tin_number' => ['nullable', 'string', 'max:30'],
        ];
    }
}
