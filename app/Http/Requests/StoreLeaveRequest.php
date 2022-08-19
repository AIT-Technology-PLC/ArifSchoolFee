<?php

namespace App\Http\Requests;

use App\Models\Employee;
use App\Rules\MustBelongToCompany;
use App\Rules\ValidateTimeOfAmount;
use Illuminate\Foundation\Http\FormRequest;

class StoreLeaveRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'leave' => ['required', 'array'],
            'leave.*.employee_id' => ['required', 'integer', 'distinct', new MustBelongToCompany('employees'), function ($attribute, $value, $fail) {
                if (!authUser()->getAllowedWarehouses('hr')->where('id', Employee::firstWhere('id', $value)->user->warehouse_id)->count()) {
                    $fail('You do not have permission to create a leave request for this employee.');
                }
            }],
            'leave.*.leave_category_id' => ['required', 'integer', new MustBelongToCompany('leave_categories')],
            'leave.*.starting_period' => ['required', 'date'],
            'leave.*.ending_period' => ['required', 'date', 'after:leave.*.starting_period'],
            'leave.*.is_paid_time_off' => ['required', 'boolean'],
            'leave.*.time_off_amount' => ['required', 'numeric', new ValidateTimeOfAmount],

        ];
    }
}