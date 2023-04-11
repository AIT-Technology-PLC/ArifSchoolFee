<?php

namespace App\Http\Requests;

use App\Models\Employee;
use App\Rules\MustBelongToCompany;
use App\Rules\ValidateTimeOffAmount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'leave.*.employee_id' => ['required', 'integer', 'distinct', new MustBelongToCompany('employees'), Rule::in(Employee::getEmployees()->pluck('id'))],
            'leave.*.leave_category_id' => ['required', 'integer', new MustBelongToCompany('leave_categories')],
            'leave.*.starting_period' => ['required', 'date'],
            'leave.*.ending_period' => ['required', 'date', 'after:leave.*.starting_period'],
            'leave.*.is_paid_time_off' => ['required', 'boolean'],
            'leave.*.time_off_amount' => ['nullable', 'numeric', 'required_if:leave.*.is_paid_time_off,1', 'gt:0', new ValidateTimeOffAmount],
            'leave.*.description' => ['nullable', 'string'],
        ];
    }
}