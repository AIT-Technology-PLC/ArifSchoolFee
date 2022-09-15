<?php

namespace App\Http\Requests;

use App\Models\Employee;
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
            'code' => ['required', 'integer', new UniqueReferenceNum('compensation_adjustments', $this->route('compensation_adjustment')->id),
                Rule::excludeIf(!userCompany()->isEditingReferenceNumberEnabled())],
            'issued_on' => ['required', 'date'],
            'starting_period' => ['required', 'date', Rule::unique('compensation_adjustments')->where('company_id', userCompany()->id)->where('id', '<>', $this->route('compensation_adjustment')->id)->withoutTrashed()],
            'ending_period' => ['required', 'date', 'after:starting_period', Rule::unique('compensation_adjustments')->where('company_id', userCompany()->id)->where('id', '<>', $this->route('compensation_adjustment')->id)->withoutTrashed()],
            'compensationAdjustment' => ['required', 'array'],
            'compensationAdjustment.*.employee_id' => ['required', 'integer', 'distinct', new MustBelongToCompany('employees'), function ($attribute, $value, $fail) {
                if (!authUser()->getAllowedWarehouses('hr')->where('id', Employee::firstWhere('id', $value)->user->warehouse_id)->count()) {
                    $fail('You do not have permission to modify an adjustment request of this employee.');
                }
            }],
            'compensationAdjustment.*.employeeAdjustments.*.compensation_id' => ['required', 'integer', new MustBelongToCompany('compensations')],
            'compensationAdjustment.*.employeeAdjustments.*.amount' => ['required', 'numeric'],
        ];
    }
}
