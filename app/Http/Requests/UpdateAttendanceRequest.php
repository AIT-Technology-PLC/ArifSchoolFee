<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAttendanceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'integer', new UniqueReferenceNum('attendances', $this->route('attendance')->id)],
            'issued_on' => ['required', 'date'],
            'starting_period' => ['required', 'date', Rule::unique('attendances')->where('warehouse_id', authUser()->warehouse_id)->whereNot('id', $this->route('attendance')->id)],
            'ending_period' => ['required', 'date', 'after:starting_period', Rule::unique('attendances')->where('warehouse_id', authUser()->warehouse_id)->whereNot('id', $this->route('attendance')->id)],
            'attendance' => ['required', 'array'],
            'attendance.*.employee_id' => ['required', 'integer', 'distinct', new MustBelongToCompany('employees')],
            'attendance.*.days' => ['required', 'numeric', 'gt:0', function ($attribute, $value, $fail) {
                $difference = number_format((new Carbon($this->get('ending_period')))->floatDiffInDays((new Carbon($this->get('starting_period')))), 2, '.', '');
                if ($value > $difference) {
                    $fail('Absent days should not be greater than the period specified.');
                }
            }],
        ];
    }
}
