<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;

class StoreCompensationAdjustmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'integer', new UniqueReferenceNum('compensation_adjustments')],
            'issued_on' => ['required', 'date'],
            'starting_period' => ['required', 'date'],
            'ending_period' => ['required', 'date', 'after:starting_period'],
            'compensationAdjustment' => ['required', 'array'],
            'compensationAdjustment.*.employee_id' => ['required', 'integer', 'distinct', new MustBelongToCompany('employees')],
            'compensationAdjustment.*.compensation_id' => ['required', 'integer', new MustBelongToCompany('employee_compensations')],
            'compensationAdjustment.*.amount' => ['required', 'numeric'],
        ];
    }
}