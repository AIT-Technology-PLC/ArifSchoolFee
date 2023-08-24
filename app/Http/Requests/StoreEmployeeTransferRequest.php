<?php

namespace App\Http\Requests;

use App\Models\Employee;
use App\Rules\CanEditReferenceNumber;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeTransferRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'integer', new UniqueReferenceNum('employee_transfers'), new CanEditReferenceNumber('employee_transfers')],
            'issued_on' => ['required', 'date', 'before_or_equal:now'],
            'employeeTransfer' => ['required', 'array'],
            'employeeTransfer.*.employee_id' => ['required', 'integer', 'distinct', new MustBelongToCompany('employees'), Rule::in(Employee::getEmployees(false)->pluck('id'))],
            'employeeTransfer.*.warehouse_id' => ['required', 'integer', new MustBelongToCompany('warehouses')],
        ];
    }
}
