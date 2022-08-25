<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use App\Rules\ValidateTimeOffAmount;
use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeLeaveRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'leave_category_id' => ['required', 'integer', new MustBelongToCompany('leave_categories')],
            'starting_period' => ['required', 'date'],
            'ending_period' => ['required', 'date', 'after:starting_period'],
            'is_paid_time_off' => ['required', 'boolean'],
            'time_off_amount' => ['nullable', 'numeric', 'required_if:is_paid_time_off,1', new ValidateTimeOffAmount(authUser()->employee->id, $this->input('is_paid_time_off'))],
        ];
    }
}
