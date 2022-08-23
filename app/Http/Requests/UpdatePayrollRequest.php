<?php

namespace App\Http\Requests;

use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePayrollRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'integer', new UniqueReferenceNum('payrolls', $this->route('payroll')->id)],
            'issued_on' => ['required', 'date'],
            'bank_name' => ['required', 'string'],
            'starting_period' => ['required', 'date', Rule::unique('payrolls')->where('warehouse_id', authUser()->warehouse_id)->whereNot('id', $this->route('payroll')->id)],
            'ending_period' => ['required', 'date', 'after:starting_period', Rule::unique('payrolls')->where('warehouse_id', authUser()->warehouse_id)->whereNot('id', $this->route('payroll')->id)],
        ];
    }
}