<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLeaveRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employee_id' => ['required', 'integer', 'distinct', new MustBelongToCompany('employees')],
            'leave_category_id' => ['required', 'integer', new MustBelongToCompany('leave_categories')],

        ];
    }
}