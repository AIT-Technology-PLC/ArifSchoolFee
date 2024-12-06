@extends('layouts.app')

@section('title', 'Send Email/SMS')

@section('content')
    <x-common.report-filter action="{{ route('messages.create') }}">
        <div class="quickview-body">
            <div class="quickview-block">
                <div class="columns is-marginless is-vcentered is-multiline is-mobile">
                    <div class="column is-6">
                        <x-forms.label>
                            Gender
                        </x-forms.label>
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id="gender"
                                    name="gender"
                                    class="is-fullwidth"
                                >
                                    <option disabled> Gender </option>
                                    <option
                                        value=""
                                        @selected(request('gender') == '')
                                    > All </option>
                                    <option
                                        value="male"
                                        @selected(request('gender') == 'male')
                                    > Male </option>
                                    <option
                                        value="female"
                                        @selected(request('gender') == 'female')
                                    > Female </option>
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label>
                            Branch
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.select
                                    id="warehouse_id"
                                    name="warehouse_id"
                                    class="is-fullwidth"
                                >
                                    <option
                                        value=" "
                                        @selected(request('warehouse_id') == '')
                                    > All </option>
                                    @foreach ($branches as $branch)
                                        <option
                                            value="{{ $branch->id }}"
                                            @selected(request('warehouse_id') == $branch->id)
                                        >{{ $branch->name }}</option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-code-branch"
                                    class="is-large is-left"
                                />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label>
                            Department
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.select
                                    id="department_id"
                                    name="department_id"
                                    class="is-fullwidth"
                                >
                                    <option
                                        value=" "
                                        @selected(request('department_id') == '')
                                    > All </option>
                                    @foreach ($departments as $department)
                                        <option
                                            value="{{ $department->id }}"
                                            @selected(request('department_id') == $department->id)
                                        >{{ $department->name }}</option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-large is-left"
                                />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label>
                            Designation
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.select
                                    id="designation_id"
                                    name="designation_id"
                                    class="is-fullwidth"
                                >
                                    <option
                                        value=" "
                                        @selected(request('designation_id') == '')
                                    > All </option>
                                    @foreach ($designations as $designation)
                                        <option
                                            value="{{ $designation->id }}"
                                            @selected(request('designation_id') == $designation->id)
                                        >{{ $designation->name }}</option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-large is-left"
                                />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label>
                            Class
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.select
                                    id="school_class_id"
                                    name="school_class_id"
                                    class="is-fullwidth"
                                >
                                    <option
                                        value=" "
                                        @selected(request('school_class_id') == '')
                                    > All </option>
                                    @foreach ($classes as $class)
                                        <option
                                            value="{{ $class->id }}"
                                            @selected(request('school_class_id') == $class->id)
                                        >{{ $class->name }}</option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-large is-left"
                                />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label>
                            Section
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.select
                                    id="section_id"
                                    name="section_id"
                                    class="is-fullwidth"
                                >
                                    <option
                                        value=" "
                                        @selected(request('section_id') == '')
                                    > All </option>
                                    @foreach ($sections as $sections)
                                        <option
                                            value="{{ $sections->id }}"
                                            @selected(request('section_id') == $sections->id)
                                        >{{ $sections->name }}</option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-large is-left"
                                />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label>
                            Student Group
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.select
                                    id="student_group_id"
                                    name="student_group_id"
                                    class="is-fullwidth"
                                >
                                    <option
                                        value=" "
                                        @selected(request('student_group_id') == '')
                                    > All </option>
                                    @foreach ($groups as $group)
                                        <option
                                            value="{{ $group->id }}"
                                            @selected(request('student_group_id') == $group->id)
                                        >{{ $group->name }}</option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-large is-left"
                                />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label>
                            Student Category
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.select
                                    id="student_category_id"
                                    name="student_category_id"
                                    class="is-fullwidth"
                                >
                                    <option
                                        value=" "
                                        @selected(request('student_category_id') == '')
                                    > All </option>
                                    @foreach ($categories as $category)
                                        <option
                                            value="{{ $category->id }}"
                                            @selected(request('student_category_id') == $category->id)
                                        >{{ $category->name }}</option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-large is-left"
                                />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </div>
        </div>
    </x-common.report-filter>
    
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-plus-circle" />
                    <span>
                        Send Email/SMS
                    </span>
                </span>
            </x-slot>
        </x-content.header> 
        <form
            id="formOne"
            action="{{ route('messages.store') }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main>
            <x-common.fail-message :message="session('failedMessage')"/>
            @if ($errors->has('base'))
                <x-common.fail-message :message="$errors->first('base')"/>
            @endif

            <div class="columns is-marginless is-multiline is-mobile">
                    <div class="column is-6-mobile is-6-tablet is-6-desktop">
                        <x-forms.field>
                            <x-forms.label for="subject">
                                Subject <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="subject"
                                    name="subject"
                                    type="text"
                                    placeholder="Message Title"
                                    value="{{ old('subject') ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-layer-group"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="subject" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6-mobile is-6-tablet is-6-desktop">
                        <x-forms.field>
                            <x-forms.label for="type">
                                Message Type <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left ">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="type"
                                    name="type"
                                >
                                    <option
                                        disabled
                                        selected
                                        value=""
                                    >
                                        Select Type
                                    </option>
                                    <option
                                        value="sms"
                                        @selected(old('type') == 'sms')
                                    >
                                        SMS
                                    </option>
                                    <option
                                        value="email"
                                        @selected(old('type') == 'email')
                                    >
                                        Email
                                    </option>
                                    <option
                                        value="both"
                                        @selected(old('type') == 'both')
                                    >
                                        Both (Email and SMS)
                                    </option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="type" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-12-mobile is-12-tablet is-6-desktop">
                        <x-forms.field>
                            <x-forms.label for="message_content">
                                Message <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.textarea
                                    class="summernote textarea"
                                    name="message_content"
                                    id="message_content"
                                    placeholder="Email or SMS Content"
                                >
                                    {{ old('message_content') ?? '' }}
                                </x-forms.textarea>
                                <x-common.validation-error property="message_content" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-12-mobile is-12-tablet is-6-desktop">
                        <x-forms.label>
                            To <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <div class="panel">
                            <p id="tabs" class="panel-tabs panel-heading is-size-6 has-text-weight-normal">
                                <a data-target="all" class="is-active">All</a>
                                <a data-target="specific_user">Specific User</a>
                                <a data-target="specific_student">Specific Student</a>
                                <a data-target="specific_staff">Specific Staff</a>
                            </p>

                            <div id="all" class="panel-block my-2 is-active">
                                <x-forms.field class="has-text-centered">
                                    <label class="checkbox mx-5 mb-2">
                                        <input
                                            type="checkbox"
                                            name="all[]"
                                            value="all_user"
                                            id="all_user"
                                            @checked(old('all_user'))
                                        >
                                        All User
                                    </label>
                                    <label class="checkbox mx-5 mb-2">
                                        <input
                                            type="checkbox"
                                            name="all[]"
                                            value="all_student"
                                            id="all_student"
                                            @checked(old('all_student'))
                                        >
                                        All Student
                                    </label>
                                    <label class="checkbox mx-5 mb-2">
                                        <input
                                            type="checkbox"
                                            name="all[]"
                                            value="all_staff"
                                            id="all_staff"
                                            @checked(old('all_staff'))
                                        >
                                        All Staff
                                    </label>
                                </x-forms.field>
                            </div>

                            <div id="specific_user" class="panel-block my-2">
                                <x-forms.field>
                                    <x-forms.control>
                                        <x-forms.select
                                            class="is-fullwidth mb-2 is-multiple" 
                                            id="employee_id"
                                            name="employee[][employee_id]"
                                            multiple="multiple"
                                            x-init="initializeSelect2($el, ' Select Specific User ')"
                                        >
                                            @foreach ($employees as $employee)
                                                <option
                                                    value="{{ $employee->id }}"
                                                    @selected(old('employee_id'))
                                                >
                                                    {{ $employee->user->name}}
                                                </option>
                                            @endforeach
                                        </x-forms.select>
                                        <x-common.validation-error property="employee.*.employee_id" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>

                            <div id="specific_student" class="panel-block my-2">
                                <x-forms.field>
                                    <x-forms.control>
                                        <x-forms.select
                                            class="is-fullwidth mb-2 is-multiple"
                                            id="student_id"
                                            name="student[][student_id]"
                                            multiple="multiple"
                                            x-init="initializeSelect2($el, ' Select Specific Student ')"
                                        >
                                            @foreach ($students as $student)
                                                <option
                                                    value="{{ $student->id }}"
                                                    @selected(old('student_id'))
                                                >
                                                    {{ str(str()->ucfirst($student->first_name))->append(' '. str()->ucfirst($student->last_name))}}
                                                </option>
                                            @endforeach
                                    </x-forms.select>
                                    <x-common.validation-error property="student.*.student_id" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>

                            <div id="specific_staff" class="panel-block my-2">
                                <x-forms.field>
                                    <x-forms.control>
                                        <x-forms.select
                                            class="is-fullwidth mb-2 is-multiple"
                                            id="staff_id"
                                            name="staff[][staff_id]"
                                            multiple="multiple"
                                            x-init="initializeSelect2($el, ' Select Specific Staff ')"
                                        >
                                            @foreach ($staffs as $staff)
                                                <option
                                                    value="{{ $staff->id }}"
                                                    @selected(old('staff_id'))
                                                >
                                                    {{ str(str()->ucfirst($staff->first_name))->append(' '. str()->ucfirst($staff->last_name))}}
                                                </option>
                                            @endforeach
                                    </x-forms.select>
                                    <x-common.validation-error property="staff.*.staff_id" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                        </div>
                    </div>
                </div>            
            </x-content.main>
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>

    <script src="{{ asset('js/message-page.js') }}"></script>
@endsection
