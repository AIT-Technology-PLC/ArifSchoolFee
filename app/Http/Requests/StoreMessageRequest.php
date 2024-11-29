<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\MustBelongToCompany;

class StoreMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject' => ['required', 'string', 'max:50'],
            'message_content' => ['required', 'string'],
            'type' => ['required', 'string', 'max:5', Rule::in(['sms', 'email', 'both'])],
            
            'all' => ['nullable', 'array'],
            'all.*.all_user' => ['nullable', 'integer'],
            'all.*.all_student' => ['nullable', 'integer'],
            'all.*.all_staff' => ['nullable', 'integer'],

            'employee' => ['nullable', 'array'],
            'employee.*.employee_id' => ['nullable', 'integer', new MustBelongToCompany('employees')],
            
            'student' => ['nullable', 'array'],
            'student.*.student_id' => ['nullable', 'integer', new MustBelongToCompany('students')],
            
            'staff' => ['nullable', 'array'],
            'staff.*.staff_id' => ['nullable', 'integer', new MustBelongToCompany('staff')],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $all = $this->input('all');
            $employee = $this->input('employee');
            $student = $this->input('student');
            $staff = $this->input('staff');

            if (empty($all) && empty($employee) && empty($student) && empty($staff)) {
                $validator->errors()->add('base', 'Please select all recipients, or choose specific individuals, to send an email or SMS.');
            }
        });
    }
}
