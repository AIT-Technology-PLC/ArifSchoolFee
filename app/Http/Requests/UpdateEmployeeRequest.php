<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'position' => ['required', 'string'],
            'enabled' => ['sometimes', 'required', 'integer', 'max:1'],
            'role' => ['sometimes', 'required', 'string', Rule::notIn(['System Manager'])],
            'warehouse_id' => ['required', 'integer', new MustBelongToCompany('warehouses')],
            'transactions' => ['nullable', 'array'],
            'read' => ['nullable', 'array'],
            'subtract' => ['nullable', 'array'],
            'add' => ['nullable', 'array'],
            'sales' => ['nullable', 'array'],
            'adjustment' => ['nullable', 'array'],
            'siv' => ['nullable', 'array'],
            'transactions.*' => ['nullable', 'integer', new MustBelongToCompany('warehouses')],
            'read.*' => ['nullable', 'integer', new MustBelongToCompany('warehouses')],
            'subtract.*' => ['nullable', 'integer', new MustBelongToCompany('warehouses')],
            'add.*' => ['nullable', 'integer', new MustBelongToCompany('warehouses')],
            'sales.*' => ['nullable', 'integer', new MustBelongToCompany('warehouses')],
            'adjustment.*' => ['nullable', 'integer', new MustBelongToCompany('warehouses')],
            'siv.*' => ['nullable', 'integer', new MustBelongToCompany('warehouses')],
            'gender' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'bank_account' => ['nullable', 'string', 'max:255'],
            'tin_number' => ['nullable', 'string', 'max:255'],
            'job_type' => ['required', 'string', 'max:255', Rule::in(['Full Time', 'Part Time', 'Contractual', 'Remote', 'Internship'])],
            'phone' => ['required', 'string', 'max:255'],
            'id_type' => ['nullable', 'string', 'max:255'],
            'id_number' => ['nullable', 'string', 'max:255'],
            'date_of_hiring' => ['required', 'date'],
            'gross_salary' => ['nullable', 'numeric', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'emergency_name' => ['nullable', 'string', 'max:255'],
            'emergency_phone' => ['nullable', 'string', 'max:255'],
            'department_id' => ['required', 'integer', new MustBelongToCompany('departments')],
        ];
    }
}