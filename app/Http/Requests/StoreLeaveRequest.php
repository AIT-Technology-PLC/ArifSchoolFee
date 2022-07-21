<?php

namespace App\Http\Requests;

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
            'leave.*.name' => ['required', 'string', 'distinct'],
            'leave.*.leave_category_id' => ['required', 'string'],
        ];
    }
}