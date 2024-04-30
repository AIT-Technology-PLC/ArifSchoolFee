<?php

namespace App\Http\Requests;

use App\Models\Compensation;
use App\Models\Employee;
use App\Rules\MustBelongToCompany;
use App\Rules\ValidateCompensationAmountIsValid;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'position' => ['required', 'string'],
            'enabled' => ['required', 'integer', 'max:1'],
            'role' => ['required', 'string', Rule::notIn(['System Manager'])],
            'warehouse_id' => ['required', 'integer', new MustBelongToCompany('warehouses')],
            'transactions' => ['nullable', 'array'],
            'read' => ['nullable', 'array'],
            'subtract' => ['nullable', 'array'],
            'add' => ['nullable', 'array'],
            'sales' => ['nullable', 'array'],
            'adjustment' => ['nullable', 'array'],
            'siv' => ['nullable', 'array'],
            'hr' => ['nullable', 'array'],
            'transactions.*' => ['nullable', 'integer', new MustBelongToCompany('warehouses')],
            'read.*' => ['nullable', 'integer', new MustBelongToCompany('warehouses')],
            'subtract.*' => ['nullable', 'integer', new MustBelongToCompany('warehouses')],
            'add.*' => ['nullable', 'integer', new MustBelongToCompany('warehouses')],
            'sales.*' => ['nullable', 'integer', new MustBelongToCompany('warehouses')],
            'adjustment.*' => ['nullable', 'integer', new MustBelongToCompany('warehouses')],
            'siv.*' => ['nullable', 'integer', new MustBelongToCompany('warehouses')],
            'hr.*' => ['nullable', 'integer', new MustBelongToCompany('warehouses')],
            'transfer_source.*' => ['nullable', 'integer', new MustBelongToCompany('warehouses')],
            'gender' => ['required', 'string', 'max:255', Rule::in(['male', 'female'])],
            'address' => ['required', 'string', 'max:255'],
            'bank_name' => ['nullable', 'string', 'max:255', 'required_unless:bank_account,null'],
            'bank_account' => ['nullable', 'string', 'max:255', 'required_unless:bank_name,null'],
            'tin_number' => ['nullable', 'string', 'max:255'],
            'job_type' => ['required', 'string', 'max:255', Rule::in(['full time', 'part time', 'contractual', 'remote', 'internship'])],
            'phone' => ['required', 'string', 'max:255'],
            'id_type' => ['nullable', 'string', 'max:255', Rule::in(['passport', 'drivers license', 'employee id', 'kebele id', 'student id'])],
            'id_number' => ['nullable', 'string', 'max:255'],
            'date_of_hiring' => ['nullable', 'date'],
            'date_of_birth' => ['nullable', 'date', 'before:' . now()],
            'emergency_name' => ['nullable', 'string', 'max:255', 'required_unless:emergency_phone,null'],
            'emergency_phone' => ['nullable', 'string', 'max:255', 'required_unless:emergency_name,null'],
            'department_id' => ['nullable', 'integer', Rule::when(!isFeatureEnabled('Department Management'), 'prohibited'), new MustBelongToCompany('departments')],
            'employeeCompensation' => [Rule::when(!isFeatureEnabled('Compensation Management'), 'prohibited'), 'array'],
            'employeeCompensation.*.compensation_id' => [Rule::when(!isFeatureEnabled('Compensation Management'), 'prohibited'), 'integer', 'distinct', Rule::in(Compensation::active()->canBeInputtedManually()->pluck('id'))],
            'employeeCompensation.*.amount' => [Rule::when(!isFeatureEnabled('Compensation Management'), 'prohibited'), 'numeric', new ValidateCompensationAmountIsValid],
            'paid_time_off_amount' => ['required', 'numeric'],
            'does_receive_sales_report_email' => ['sometimes', 'required', 'boolean', Rule::prohibitedIf($this->boolean('does_receive_sales_report_email') && limitReached('sales-report-email-recipient', Employee::salesReportEmailRecipent()->count()))],
        ];
    }
}
