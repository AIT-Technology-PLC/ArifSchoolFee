<?php

namespace App\Http\Requests;

use App\Models\Compensation;
use App\Rules\MustBelongToCompany;
use App\Rules\ValidateCompensationAmountIsValid;
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
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->route('employee')->user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'position' => ['required', 'string'],
            'enabled' => ['sometimes', 'required', 'integer', 'max:1', Rule::when(authUser()->id == $this->route('employee')->user->id || $this->route('employee')->user->hasRole('System Manager'), 'prohibited')],
            'role' => ['sometimes', 'required', 'string', Rule::notIn(['System Manager']), Rule::when(authUser()->id == $this->route('employee')->user->id || $this->route('employee')->user->hasRole('System Manager'), 'prohibited')],
            'warehouse_id' => ['sometimes', 'required', 'integer', Rule::when(isFeatureEnabled('Employee Transfer'), 'prohibited'), new MustBelongToCompany('warehouses')],
            'transactions' => ['nullable', 'array', Rule::when($this->route('employee')->user->hasRole('System Manager'), 'prohibited')],
            'read' => ['nullable', 'array', Rule::when($this->route('employee')->user->hasRole('System Manager'), 'prohibited')],
            'subtract' => ['nullable', 'array', Rule::when($this->route('employee')->user->hasRole('System Manager'), 'prohibited')],
            'add' => ['nullable', 'array', Rule::when($this->route('employee')->user->hasRole('System Manager'), 'prohibited')],
            'sales' => ['nullable', 'array', Rule::when($this->route('employee')->user->hasRole('System Manager'), 'prohibited')],
            'adjustment' => ['nullable', 'array', Rule::when($this->route('employee')->user->hasRole('System Manager'), 'prohibited')],
            'siv' => ['nullable', 'array', Rule::when($this->route('employee')->user->hasRole('System Manager'), 'prohibited')],
            'hr' => ['nullable', 'array', Rule::when($this->route('employee')->user->hasRole('System Manager'), 'prohibited')],
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
        ];
    }
}
