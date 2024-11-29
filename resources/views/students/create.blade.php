@extends('layouts.app')

@section('title', 'Create New Student')

@section('content')
<x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-plus-circle" />
                    <span>
                        New Student
                    </span>
                </span>
            </x-slot>
        </x-content.header>
        <form
            id="formOne"
            action="{{ route('students.store') }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main>
                <ul class="steps is-medium is-centered has-content-centered">
                    <li class="steps-segment is-active has-gaps" data-target="step-one">
                        <a class="text-green">
                            <span class="steps-marker">
                                <span class="icon">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                            </span>
                            <div class="steps-content">
                                <p class="heading">Basic Information</p>
                            </div>
                        </a>
                    </li>
                    <li class="steps-segment has-gaps" data-target="step-two">
                        <a class="text-green">
                            <span class="steps-marker">
                            <span class="icon">
                                <i class="fas fa-address-card"></i>
                            </span>
                            </span>
                            <div class="steps-content">
                                <p class="heading">Personal Detail</p>
                            </div>
                        </a>
                    </li>
                    <li class="steps-segment has-gaps" data-target="step-last">
                        <a class="text-green">
                            <span class="steps-marker">
                            <span class="icon">
                                <i class="fas fa-clipboard-list"></i>
                            </span>
                            </span>
                            <div class="steps-content">
                                <p class="heading">Address and Transport</p>
                            </div>
                        </a>
                    </li>
                </ul>

                <div id="step-one" class="step-content is-active">
                    <div class="columns is-marginless is-multiline is-mobile">
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-forms.field>
                                <x-forms.label for="code">
                                    Admission No <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        type="number"
                                        name="code"
                                        id="code"
                                        readonly
                                        value="{{ $currentStudentCode }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-hashtag"
                                        class="is-large is-left"
                                    />
                                    <x-common.validation-error property="code" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-forms.field>
                                <x-forms.label for="warehouse_id">
                                    Branch <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left ">
                                    <x-forms.select
                                        class="is-fullwidth"
                                        id="warehouse_id"
                                        name="warehouse_id"
                                    >
                                        <option
                                            disabled
                                            selected
                                        >
                                            Select Branch
                                        </option>
                                        @foreach ($branches as $branch)
                                            <option
                                                value="{{ $branch->id }}"
                                                @selected($branch->id == (old('warehouse_id') ?? ''))
                                            >
                                                {{ str()->ucfirst($branch->name) }}
                                            </option>
                                        @endforeach
                                    </x-forms.select>
                                    <x-common.icon
                                        name="fas fa-sort"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="warehouse_id" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-forms.field>
                                <x-forms.label for="school_class_id">
                                    Class <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left ">
                                    <x-forms.select
                                        class="is-fullwidth"
                                        id="school_class_id"
                                        name="school_class_id"
                                    >
                                        <option
                                            disabled
                                            selected
                                        >
                                            Select Class
                                        </option>
                                        @foreach ($classes as $class)
                                            <option
                                                value="{{ $class->id }}"
                                                @selected($class->id == (old('school_class_id') ?? ''))
                                            >
                                                {{ str()->ucfirst($class->name) }}
                                            </option>
                                        @endforeach
                                    </x-forms.select>
                                    <x-common.icon
                                        name="fas fa-sort"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="school_class_id" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-forms.field>
                                <x-forms.label for="section_id">
                                    Section <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left ">
                                    <x-forms.select
                                        class="is-fullwidth"
                                        id="section_id"
                                        name="section_id"
                                    >
                                        <option
                                            disabled
                                            selected
                                        >
                                            Select Section
                                        </option>
                                        @foreach ($sections as $sections)
                                            <option
                                                value="{{ $sections->id }}"
                                                @selected($sections->id == (old('section_id') ?? ''))
                                            >
                                                {{ str()->ucfirst($sections->name) }}
                                            </option>
                                        @endforeach
                                    </x-forms.select>
                                    <x-common.icon
                                        name="fas fa-sort"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="section_id" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-forms.field>
                                <x-forms.label for="student_category_id">
                                    Student Category <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left ">
                                    <x-forms.select
                                        class="is-fullwidth"
                                        id="student_category_id"
                                        name="student_category_id"
                                    >
                                        <option
                                            disabled
                                            selected
                                        >
                                            Select Student Category
                                        </option>
                                        @foreach ($categories as $category)
                                            <option
                                                value="{{ $category->id }}"
                                                @selected($category->id == (old('student_category_id') ?? ''))
                                            >
                                                {{ str()->ucfirst($category->name) }}
                                            </option>
                                        @endforeach
                                    </x-forms.select>
                                    <x-common.icon
                                        name="fas fa-sort"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="student_category_id" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-forms.field>
                                <x-forms.label for="student_group_id">
                                    Student Group <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left ">
                                    <x-forms.select
                                        class="is-fullwidth"
                                        id="student_group_id"
                                        name="student_group_id"
                                    >
                                        <option
                                            disabled
                                            selected
                                        >
                                            Select Student Group
                                        </option>
                                        @foreach ($groups as $group)
                                            <option
                                                value="{{ $group->id }}"
                                                @selected($group->id == (old('student_group_id') ?? ''))
                                            >
                                                {{ str()->ucfirst($group->name) }}
                                            </option>
                                        @endforeach
                                    </x-forms.select>
                                    <x-common.icon
                                        name="fas fa-sort"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="student_group_id" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                    </div>
                </div>

                <div id="step-two" class="step-content">
                    <div class="columns is-marginless is-multiline is-mobile">
                        <div class="column is-6-mobile is-6-tablet is-3-desktop">
                            <x-forms.field>
                                <x-forms.label for="first_name">
                                    First Name <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        id="first_name"
                                        name="first_name"
                                        type="text"
                                        placeholder="First Name"
                                        value="{{ old('first_name') }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-user"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="first_name" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-3-desktop">
                            <x-forms.field>
                                <x-forms.label for="last_name">
                                    Last Name <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        id="last_name"
                                        name="last_name"
                                        type="text"
                                        placeholder="Last Name"
                                        value="{{ old('last_name') }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-user"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="last_name" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-3-desktop">
                            <x-forms.field>
                                <x-forms.label for="gender">
                                    Gender <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left ">
                                    <x-forms.select
                                        class="is-fullwidth"
                                        id="gender"
                                        name="gender"
                                    >
                                        <option
                                            disabled
                                            selected
                                        >
                                            Select Gender
                                        </option>
                                        <option
                                            value="male"
                                            @selected(old('gender') == 'male')
                                        >
                                            Male
                                        </option>
                                        <option
                                            value="female"
                                            @selected(old('gender') == 'female')
                                        >
                                            Female
                                        </option>
                                    </x-forms.select>
                                    <x-common.icon
                                        name="fas fa-sort"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="gender" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-3-desktop">
                            <x-forms.field>
                                <x-forms.label for="email">
                                    Email <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        id="email"
                                        name="email"
                                        type="email"
                                        placeholder="Email Address"
                                        value="{{ old('email') }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-at"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="email" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-3-desktop">
                            <x-forms.field>
                                <x-forms.label for="phone">
                                    Phone <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        id="phone"
                                        name="phone"
                                        type="number"
                                        placeholder="Phone/Telephone"
                                        value="{{ old('phone') }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-phone"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="phone" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-3-desktop">
                            <x-forms.field>
                                <x-forms.label for="date_of_birth">
                                    Date of Birth <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left is-fullwidth">
                                    <x-forms.input
                                        class="is-fullwidth"
                                        id="date_of_birth"
                                        name="date_of_birth"
                                        type="date"
                                        placeholder="mm/dd/yyyy"
                                        value="{{ old('date_of_birth') ?? null }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-calendar-alt"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="date_of_birth" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-3-desktop">
                            <x-forms.label>
                                Father Name <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.field class="has-addons">
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        id="father_name"
                                        name="father_name"
                                        type="text"
                                        placeholder="Father Name"
                                        value="{{ old('father_name') }}"
                                        autocomplete="father_name"
                                    />
                                    <x-common.icon
                                        name="fas fa-user"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="father_name" />
                                </x-forms.control>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        id="father_phone"
                                        name="father_phone"
                                        type="phone"
                                        placeholder="Father Phone"
                                        value="{{ old('father_phone') }}"
                                        autocomplete="father_phone"
                                    />
                                    <x-common.icon
                                        name="fas fa-phone"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="father_phone" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-3-desktop">
                            <x-forms.label>
                                Mother Name <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.field class="has-addons">
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        id="mother_name"
                                        name="mother_name"
                                        type="text"
                                        placeholder="Mother Name"
                                        value="{{ old('mother_name') }}"
                                        autocomplete="mother_name"
                                    />
                                    <x-common.icon
                                        name="fas fa-user"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="mother_name" />
                                </x-forms.control>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        id="mother_phone"
                                        name="mother_phone"
                                        type="phone"
                                        placeholder="Mother Phone"
                                        value="{{ old('mother_phone') }}"
                                        autocomplete="mother_phone"
                                    />
                                    <x-common.icon
                                        name="fas fa-phone"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="mother_phone" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                    </div>
                </div>

                <div id="step-last" class="step-content">
                    <div class="columns is-marginless is-multiline is-mobile">
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-forms.field>
                                <x-forms.label for="academic_year_id">
                                    Academic Year <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left ">
                                    <x-forms.select
                                        class="is-fullwidth"
                                        id="academic_year_id"
                                        name="academic_year_id"
                                    >
                                        <option
                                            disabled
                                            selected
                                        >
                                            Select Academic Year
                                        </option>
                                        @foreach ($academicYears as $academicYear)
                                            <option
                                                value="{{ $academicYear->id }}"
                                                @selected($academicYear->id == (old('academic_year_id') ?? ''))
                                            >
                                                {{ str()->ucfirst($academicYear->year) }}
                                            </option>
                                        @endforeach
                                    </x-forms.select>
                                    <x-common.icon
                                        name="fas fa-sort"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="academic_year_id" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-forms.field>
                                <x-forms.label for="admission_date">
                                    Admission Date <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left is-fullwidth">
                                    <x-forms.input
                                        class="is-fullwidth"
                                        id="admission_date"
                                        name="admission_date"
                                        type="date"
                                        placeholder="mm/dd/yyyy"
                                        value="{{ old('admission_date') ?? null }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-calendar-alt"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="admission_date" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-forms.field>
                                <x-forms.label for="route_id">
                                    Route <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left ">
                                    <x-forms.select
                                        class="is-fullwidth"
                                        id="route_id"
                                        name="route_id"
                                    >
                                        <option
                                            disabled
                                            selected
                                        >
                                            Select Route
                                        </option>
                                        @foreach ($routes as $route)
                                            <option
                                                value="{{ $route->id }}"
                                                @selected($route->id == (old('route_id') ?? ''))
                                            >
                                                {{ str()->ucfirst($route->title) }}
                                            </option>
                                        @endforeach
                                    </x-forms.select>
                                    <x-common.icon
                                        name="fas fa-sort"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="route_id" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-forms.field>
                                <x-forms.label for="vehicle_id">
                                    Vehicle <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left ">
                                    <x-forms.select
                                        class="is-fullwidth"
                                        id="vehicle_id"
                                        name="vehicle_id"
                                    >
                                        <option
                                            disabled
                                            selected
                                        >
                                            Select Vehicle
                                        </option>
                                        @foreach ($vehicles as $vehicle)
                                            <option
                                                value="{{ $vehicle->id }}"
                                                @selected($vehicle->id == (old('vehicle_id') ?? ''))
                                            >
                                                {{ str()->ucfirst($vehicle->vehicle_number) }}
                                            </option>
                                        @endforeach
                                    </x-forms.select>
                                    <x-common.icon
                                        name="fas fa-sort"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="vehicle_id" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-forms.field>
                                <x-forms.label for="current_address">
                                    Current Address <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        id="current_address"
                                        name="current_address"
                                        type="text"
                                        placeholder="Current Address"
                                        value="{{ old('current_address') }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-map-marker-alt"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="current_address" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-forms.field>
                                <x-forms.label for="permanent_address">
                                    Permanent Address <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        id="permanent_address"
                                        name="permanent_address"
                                        type="text"
                                        placeholder="Permanent Address"
                                        value="{{ old('permanent_address') }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-map-marker-alt"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="permanent_address" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                    </div>
                </div>

            </x-content.main>
            <x-content.footer>
                <div class="has-text-right">
                    <button id="prevButton" class="button bg-softblue has-text-white" style="display: none;">
                        <span class="mr-2"><i class="fas fa-arrow-left"></i></span>Back
                    </button>
                    <button id="nextButton" class="button bg-softblue has-text-white">
                        <span class="mr-2"><i class="fas fa-arrow-right"></i></span>Next
                    </button>
                    <button id="saveButton" class="button bg-softblue has-text-white" style="display: none;">
                        <span class="mr-2"><i class="fas fa-save"></i></span>Submit
                    </button>
                </div>                    
            </x-content.footer>
        </form>  
    </x-common.content-wrapper>

    <script src="{{ asset('js/steps-component.js') }}"></script>
@endsection

