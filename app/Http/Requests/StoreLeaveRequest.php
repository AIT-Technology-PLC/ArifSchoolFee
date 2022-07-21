<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
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
            'leave.*.employee_id' => ['required', 'integer', 'distinct', new MustBelongToCompany('employees')],
            'leave.*.leave_category_id' => ['required', 'integer', new MustBelongToCompany('leave_categories')],
        ];
    }
}