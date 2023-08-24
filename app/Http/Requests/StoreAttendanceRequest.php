<?php

namespace App\Http\Requests;

use App\Models\Attendance;
use App\Models\Employee;
use App\Rules\CanEditReferenceNumber;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAttendanceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'integer', new UniqueReferenceNum('attendances'), new CanEditReferenceNumber('attendances')],
            'issued_on' => ['required', 'date', 'before_or_equal:now'],
            'starting_period' => ['required', 'date', function ($attribute, $value, $fail) {
                if (Attendance::approved()->notCancelled()->where('warehouse_id', authUser()->warehouse_id)->where('ending_period', '>=', $value)->exists()) {
                    $fail('This starting period is already taken.');
                }
            }],
            'ending_period' => ['required', 'date', 'after:starting_period'],
            'attendance' => ['nullable', 'array'],
            'attendance.*.employee_id' => ['nullable', 'integer', 'distinct', 'required_unless:attendance.*.days,null', new MustBelongToCompany('employees'), Rule::in(Employee::getEmployees()->pluck('id'))],
            'attendance.*.days' => ['nullable', 'numeric', 'gt:0', 'required_unless:attendance.*.employee_id,null', function ($attribute, $value, $fail) {
                $difference = number_format((new Carbon($this->get('ending_period')))->floatDiffInDays((new Carbon($this->get('starting_period')))), 2, '.', '');
                if ($value > $difference) {
                    $fail('Absent days should not be greater than the period specified.');
                }
            }],
        ];
    }
}
