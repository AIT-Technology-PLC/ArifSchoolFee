<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'integer', new UniqueReferenceNum('attendances')],
            'date' => ['required', 'date'],
            'attendance' => ['required', 'array'],
            'attendance.*.employee_id' => ['required', 'integer', 'distinct', new MustBelongToCompany('employees')],
            'attendance.*.days' => ['required', 'numeric', 'gt:0'],
        ];
    }
}