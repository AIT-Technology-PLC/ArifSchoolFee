<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompensationAdjustmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'integer', new UniqueReferenceNum('compensation_adjustments', $this->route('compensation_adjustment')->id)],
            'issued_on' => ['required', 'date'],
            'starting_period' => ['required', 'date', Rule::unique('compensation_adjustments')->where(function ($query) {
                return $query->where('company_id', userCompany()->id)->where('id', '<>', $this->route('compensation_adjustment')->id);
            })],
            'ending_period' => ['required', 'date', 'after:starting_period', Rule::unique('compensation_adjustments')->where(function ($query) {
                return $query->where('company_id', userCompany()->id)->where('id', '<>', $this->route('compensation_adjustment')->id);
            })],
            'compensationAdjustment' => ['required', 'array'],
            'compensationAdjustment.*.employee_id' => ['required', 'integer', 'distinct', new MustBelongToCompany('employees')],
            'compensationAdjustment.*.employeeAdjustments.*.compensation_id' => ['required', 'integer', new MustBelongToCompany('compensations')],
            'compensationAdjustment.*.employeeAdjustments.*.amount' => ['required', 'numeric'],
        ];
    }
}