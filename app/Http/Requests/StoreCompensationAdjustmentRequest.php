<?php

namespace App\Http\Requests;

use App\Models\Compensation;
use App\Models\CompensationAdjustment;
use App\Models\Employee;
use App\Rules\CanEditReferenceNumber;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use App\Rules\ValidateCompensationAmountIsValid;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCompensationAdjustmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'integer', new UniqueReferenceNum('compensation_adjustments'),
                new CanEditReferenceNumber('compensation_adjustments')],
            'issued_on' => ['required', 'date', 'before_or_equal:now'],
            'starting_period' => ['required', 'date', function ($attribute, $value, $fail) {
                if (CompensationAdjustment::approved()->notCancelled()->where('ending_period', '>=', $value)->exists()) {
                    $fail('This starting period is already taken.');
                }
            }],
            'ending_period' => ['required', 'date', 'after:starting_period'],
            'compensationAdjustment' => ['required', 'array'],
            'compensationAdjustment.*.employeeAdjustments' => ['required', 'array'],
            'compensationAdjustment.*.employee_id' => ['required', 'integer', 'distinct', new MustBelongToCompany('employees'), Rule::exists('employee_compensations')->withoutTrashed(), function ($attribute, $value, $fail) {
                if (!authUser()->getAllowedWarehouses('hr')->where('id', Employee::firstWhere('id', $value)->user->warehouse_id)->count()) {
                    $fail('You do not have permission to create an adjustment request for this employee.');
                }
            }],
            'compensationAdjustment.*.employeeAdjustments.*.compensation_id' => ['required', 'integer', Rule::in(Compensation::active()->canBeInputtedManually()->adjustable()->pluck('id'))],
            'compensationAdjustment.*.employeeAdjustments.*.amount' => ['required', 'numeric', new ValidateCompensationAmountIsValid],
            'compensationAdjustment.*.employeeAdjustments.*.description' => ['nullable', 'string'],
            'compensationAdjustment.*.employeeAdjustments.*.options.overtime_period' => ['sometimes', 'required', 'string'],
        ];
    }
}
