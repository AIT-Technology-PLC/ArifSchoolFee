<?php

namespace App\Services\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmailMessage;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Staff;
use App\Models\Student;
use App\Models\Message;
use App\Models\Employee;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\StudentCategory;
use App\Models\StudentGroup;
use App\Models\Warehouse;
use App\Utilities\Sms;

class MessageService
{
    public function create($request)
    {
        $branches = Warehouse::withoutGlobalScopes([ActiveWarehouseScope::class])->get();
        $departments = Department::orderBy('name')->get(['id', 'name']);
        $designations = Designation::orderBy('name')->get(['id', 'name']);
        $classes = SchoolClass::orderBy('name')->get(['id', 'name']);
        $sections = Section::orderBy('name')->get(['id', 'name']);
        $categories = StudentCategory::orderBy('name')->get(['id', 'name']);
        $groups = StudentGroup::orderBy('name')->get(['id', 'name']);

        // Apply filters to User query
        $employees = Employee::enabled()->orderBy('id')
        ->when(isset($request['gender']) && !is_null($request['gender']), fn($query) => $query->where('gender', $request['gender']))
        ->when(isset($request['warehouse_id']) && !is_null($request['warehouse_id']), fn($query) => $query->where('warehouse_id', $request['warehouse_id']))
        ->get();

        // Apply filters to Staff query
        $staff = Staff::orderBy('first_name')
        ->when(isset($request['gender']) && !is_null($request['gender']), fn($query) => $query->where('gender', $request['gender']))
        ->when(isset($request['warehouse_id']) && !is_null($request['warehouse_id']), fn($query) => $query->where('warehouse_id', $request['warehouse_id']))
        ->when(isset($request['department_id']) && !is_null($request['department_id']), fn($query) => $query->where('department_id', $request['department_id']))
        ->when(isset($request['designation_id']) && !is_null($request['designation_id']), fn($query) => $query->where('designation_id', $request['designation_id']))
        ->get();

        // Apply filters to Student query
        $students = Student::orderBy('first_name')
        ->when(isset($request['gender']) && !is_null($request['gender']), fn($query) => $query->where('gender', $request['gender']))
        ->when(isset($request['warehouse_id']) && !is_null($request['warehouse_id']), fn($query) => $query->where('warehouse_id', $request['warehouse_id']))
        ->when(isset($request['school_class_id']) && !is_null($request['school_class_id']), fn($query) => $query->where('school_class_id', $request['school_class_id']))
        ->when(isset($request['section_id']) && !is_null($request['section_id']), fn($query) => $query->where('section_id', $request['section_id']))
        ->when(isset($request['student_category_id']) && !is_null($request['student_category_id']), fn($query) => $query->where('student_category_id', $request['student_category_id']))
        ->when(isset($request['student_group_id']) && !is_null($request['student_group_id']), fn($query) => $query->where('student_group_id', $request['student_group_id']))
        ->get();

        return [
            'branches' => $branches,
            'departments' => $departments,
            'designations' => $designations,
            'classes' => $classes,
            'sections' => $sections,
            'categories' => $categories,
            'groups' => $groups,
            'employees' => $employees,
            'staffs' => $staff,
            'students' => $students,
        ];
    }

    public function store($request)
    {
        if (!userCompany()->canSendEmailNotification() && !userCompany()->canSendSmsAlert()) {
            return redirect()->back()->with('failedMessage', 'Unable to send email or SMS because the email and SMS settings are turned off !');
        }

        if (empty($request->all) && empty($request->employee) && empty($request->student) && empty($request->staff)) {
            return [false, 'Unable to send the message please try again.'];
        }

        DB::transaction(function () use ($request) {
            if (isset($request->all)) {
                foreach ($request->all as $receiver) {

                    if ($receiver == 'all_user') {
                           $employees = Employee::enabled()->orderBy('id')->get();
                        $phoneNumbers = [];

                        foreach ($employees as $employee) {
                            $phoneNumbers[] = str($employee->phone)->after('0')->prepend('00251')->toString();

                            if (userCompany()->canSendEmailNotification() && ($request->type == 'email' || $request->type == 'both')) {
                                Mail::to($employee->user->email)->send(new SendEmailMessage($request->subject, $employee->user->name, $request->message_content));
                            }
                        }

                        if (userCompany()->canSendSmsAlert() && ($request->type == 'sms' || $request->type == 'both')) {
                            Sms::sendBulkMessage($phoneNumbers, $request->message_content);
                        }

                        $request['received_by'] = 'All User';
                        $message = Message::create($request->safe()->except('message'));
                        $message->messageDetails()->create([
                            'received_by' => $request['received_by'],
                        ]);
                    }

                    if ($receiver == 'all_student') {
                        $students = Student::orderBy('first_name')->get();
                        $phoneNumbers = [];
                        
                        foreach ($students as $student) {
                            $phoneNumbers[] = str($student->phone)->after('0')->prepend('00251')->toString();

                            if (userCompany()->canSendEmailNotification() && ($request->type == 'email' || $request->type == 'both')) {
                                Mail::to($student->email)->send(new SendEmailMessage($request->subject, str($student->first_name)->append(' '. $student->father_name), $request->message_content));
                            }
                        }

                        if (userCompany()->canSendSmsAlert() && ( $request->type == 'sms' || $request->type == 'both')) {
                            Sms::sendBulkMessage($phoneNumbers, $request->message_content);
                        }

                        $request['received_by'] = 'All Student';
                        $message = Message::create($request->safe()->except('message'));
                        $message->messageDetails()->create([
                            'received_by' => $request['received_by'],
                        ]);
                    }

                    if ($receiver == 'all_staff') {
                        $staffs = Staff::orderBy('first_name')->get();
                        $phoneNumbers = [];

                        foreach ($staffs as $staff) {
                            $phoneNumbers[] = str($staff->phone)->after('0')->prepend('00251')->toString();

                            if (userCompany()->canSendEmailNotification() && ($request->type == 'email' || $request->type == 'both')) {
                                Mail::to($staff->email)->send(new SendEmailMessage($request->subject, str($staff->first_name)->append(' '. $staff->father_name), $request->message_content));
                            }
                        }

                        if (userCompany()->canSendSmsAlert() && ($request->type == 'sms' || $request->type == 'both')) {
                            Sms::sendBulkMessage($phoneNumbers, $request->message_content);
                        }

                        $request['received_by'] = 'All Staff';
                        $message = Message::create($request->safe()->except('message'));
                        $message->messageDetails()->create([
                            'received_by' => $request['received_by'],
                        ]);
                    }
                }
            }

            elseif (isset($request->employee)) {
                foreach ($request->employee as $receiver) {
                    $employee = Employee::where('id', $receiver['employee_id'])->first();
                    
                    if (userCompany()->canSendEmailNotification() && ($request->type == 'email' || $request->type == 'both')) {
                        Mail::to($employee->user->email)->send(new SendEmailMessage($request->subject, $employee->user->name, $request->message_content));
                    }

                    elseif (userCompany()->canSendSmsAlert() && ($request->type == 'sms' || $request->type == 'both')) {
                        Sms::sendSingleMessage(str($employee->phone)->after('0')->prepend('00251')->toString(), $request->message_content);
                    }
                }

                $message = Message::create($request->safe()->except('message'));
                $message->messageDetails()->createMany($request->validated('employee'));
            }

            elseif (isset($request->student)) {
                foreach ($request->student as $receiver) {
                    $student = Student::where('id', $receiver['student_id'])->first();
                    
                    if (userCompany()->canSendEmailNotification() && ($request->type == 'email' || $request->type == 'both')) {
                        Mail::to($student->email)->send(new SendEmailMessage($request->subject, str($student->first_name)->append(' '. $student->father_name), $request->message_content));
                    }
                    
                    elseif (userCompany()->canSendSmsAlert() && ($request->type == 'sms' || $request->type == 'both')) {
                        Sms::sendSingleMessage(str($student->phone)->after('0')->prepend('00251')->toString(), $request->message_content);
                    }
                }

                $message = Message::create($request->safe()->except('message'));
                $message->messageDetails()->createMany($request->validated('student'));
            }

            elseif (isset($request->staff)) {
                foreach ($request->staff as $receiver) {
                    $staff = Staff::where('id', $receiver['staff_id'])->first();
                    
                    if (userCompany()->canSendEmailNotification() && ($request->type == 'email' || $request->type == 'both')) {
                        Mail::to($staff->email)->send(new SendEmailMessage($request->subject, str($staff->first_name)->append(' '. $staff->father_name), $request->message_content));
                    }
                    elseif (userCompany()->canSendSmsAlert() && ($request->type == 'sms' || $request->type == 'both')) {
                        Sms::sendSingleMessage(str($staff->phone)->after('0')->prepend('00251')->toString(), $request->message_content);
                    }
                }

                $message = Message::create($request->safe()->except('message'));
                $message->messageDetails()->createMany($request->validated('staff'));
            }
        });

        return [true, ''];
    }
}