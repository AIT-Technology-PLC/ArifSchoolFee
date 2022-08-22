<?php

namespace App\Http\Requests;

use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePayrollRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'integer', new UniqueReferenceNum('payrolls')],
            'issued_on' => ['required', 'date'],
            'bank' => ['required', 'string'],
            'starting_period' => ['required', 'date', Rule::unique('payrolls')->where('warehouse_id', authUser()->warehouse_id)],
            'ending_period' => ['required', 'date', 'after:starting_period', Rule::unique('payrolls')->where('warehouse_id', authUser()->warehouse_id)],
        ];
    }
}