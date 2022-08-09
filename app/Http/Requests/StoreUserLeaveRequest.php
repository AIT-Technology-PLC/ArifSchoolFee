<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserLeaveRequest extends FormRequest
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
        ];
    }
}