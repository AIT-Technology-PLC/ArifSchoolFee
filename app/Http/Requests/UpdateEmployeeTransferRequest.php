<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeTransferRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'integer', new UniqueReferenceNum('employee_transfers', $this->route('employee_transfer')->id)],
            'issued_on' => ['required', 'date'],
            'employeeTransfer' => ['required', 'array'],
            'employeeTransfer.*.employee_id' => ['required', 'integer', 'distinct', new MustBelongToCompany('employees')],
            'employeeTransfer.*.warehouse_id' => ['required', 'integer', new MustBelongToCompany('warehouses')],
        ];
    }
}
