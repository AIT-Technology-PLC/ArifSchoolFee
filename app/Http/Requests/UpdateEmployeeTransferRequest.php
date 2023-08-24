<?php

namespace App\Http\Requests;

use App\Models\Employee;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeTransferRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'integer', new UniqueReferenceNum('employee_transfers', $this->route('employee_transfer')->id),
                Rule::excludeIf(!userCompany()->isEditingReferenceNumberEnabled())],
            'issued_on' => ['required', 'date', 'before_or_equal:now'],
            'employeeTransfer' => ['required', 'array'],
            'employeeTransfer.*.employee_id' => ['required', 'integer', 'distinct', new MustBelongToCompany('employees'), Rule::in(Employee::getEmployees(false)->pluck('id'))],
            'employeeTransfer.*.warehouse_id' => ['required', 'integer', new MustBelongToCompany('warehouses')],
        ];
    }
}
