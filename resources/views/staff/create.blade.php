@extends('layouts.app')

@section('title', 'Create New Staff')

@section('content')
<x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-plus-circle" />
                    <span>
                        New Staff
                    </span>
                </span>
            </x-slot>
        </x-content.header>
        <form
            id="formOne"
            action="{{ route('staff.store') }}"
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
                                <i class="fas fa-money-bill-wave"></i>
                            </span>
                            </span>
                            <div class="steps-content">
                                <p class="heading">Payroll Detail</p>
                            </div>
                        </a>
                    </li>
                </ul>

                <div id="step-one" class="step-content is-active">
                    <div class="columns is-marginless is-multiline is-mobile">
                        <div  class="column is-6-mobile is-6-tablet is-6-desktop">
                            <x-forms.field>
                                <x-forms.label for="code">
                                    Staff No <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        type="text"
                                        name="code"
                                        id="code"
                                        readonly
                                        value="{{ $currentStaffCode }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-hashtag"
                                        class="is-large is-left"
                                    />
                                    <x-common.validation-error property="code" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-6-desktop">
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
                        <div class="column is-6-mobile is-6-tablet is-6-desktop">
                            <x-forms.field>
                                <x-forms.label for="department_id">
                                    Department <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left ">
                                    <x-forms.select
                                        class="is-fullwidth"
                                        id="department_id"
                                        name="department_id"
                                    >
                                        <option
                                            disabled
                                            selected
                                        >
                                            Select Department
                                        </option>
                                        @foreach ($departments as $department)
                                            <option
                                                value="{{ $department->id }}"
                                                @selected($department->id == (old('department_id') ?? ''))
                                            >
                                                {{ str()->ucfirst($department->name) }}
                                            </option>
                                        @endforeach
                                    </x-forms.select>
                                    <x-common.icon
                                        name="fas fa-sort"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="department_id" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-6-desktop">
                            <x-forms.field>
                                <x-forms.label for="designation_id">
                                    Designation <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left ">
                                    <x-forms.select
                                        class="is-fullwidth"
                                        id="designation_id"
                                        name="designation_id"
                                    >
                                        <option
                                            disabled
                                            selected
                                        >
                                            Select Designation
                                        </option>
                                        @foreach ($designations as $designation)
                                            <option
                                                value="{{ $designation->id }}"
                                                @selected($designation->id == (old('designation_id') ?? ''))
                                            >
                                                {{ str()->ucfirst($designation->name) }}
                                            </option>
                                        @endforeach
                                    </x-forms.select>
                                    <x-common.icon
                                        name="fas fa-sort"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="designation_id" />
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
                                <x-forms.label for="father_name">
                                    Father Name <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        id="father_name"
                                        name="father_name"
                                        type="text"
                                        placeholder="Father Name"
                                        value="{{ old('father_name') }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-user"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="father_name" />
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
                                <x-forms.label for="mother_name">
                                    Mother Name <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        id="mother_name"
                                        name="mother_name"
                                        type="text"
                                        placeholder="Mother Name"
                                        value="{{ old('mother_name') }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-user"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="mother_name" />
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
                                        type="tel"
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
                                <x-forms.label for="marital_status">
                                   Marital Status <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left ">
                                    <x-forms.select
                                        class="is-fullwidth"
                                        id="marital_status"
                                        name="marital_status"
                                    >
                                        <option
                                            disabled
                                            selected
                                        >
                                        Select Status</option>
                                        <option
                                            value="married"
                                            @selected(old('marital_status') == 'married')
                                        >
                                            Married
                                        </option>
                                        <option
                                            value="single"
                                            @selected(old('marital_status') == 'single')
                                        >
                                            Single
                                        </option>
                                        <option
                                            value="divorced"
                                            @selected(old('marital_status') == 'divorced')
                                        >
                                            Divorced
                                        </option>
                                        <option
                                            value="widowed"
                                            @selected(old('marital_status') == 'widowed')
                                        >
                                            Widowed
                                        </option>
                                    </x-forms.select>
                                    <x-common.icon
                                        name="fas fa-layer-group"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="marital_status" />
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
                            <x-forms.field>
                                <x-forms.label for="current_address">
                                    Current Address <sup class="has-text-danger">*</sup>
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
                        <div class="column is-6-mobile is-6-tablet is-3-desktop">
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

                <div id="step-last" class="step-content">
                    <div class="columns is-marginless is-multiline is-mobile">
                        <div class="column is-6-mobile is-6-tablet is-3-desktop">
                            <x-forms.field>
                                <x-forms.label for="date_of_joining">
                                    Date of Joining <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left is-fullwidth">
                                    <x-forms.input
                                        class="is-fullwidth"
                                        id="date_of_joining"
                                        name="date_of_joining"
                                        type="date"
                                        placeholder="mm/dd/yyyy"
                                        value="{{ old('date_of_joining') ?? now()->toDateString() }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-calendar-alt"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="date_of_joining" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-3-desktop">
                            <x-forms.field>
                                <x-forms.label for="qualifications">
                                    Qualifications <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        id="qualifications"
                                        name="qualifications"
                                        type="text"
                                        placeholder="Qualifications"
                                        value="{{ old('qualifications') }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-heading"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="qualifications" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-3-desktop">
                            <x-forms.field>
                                <x-forms.label for="experience">
                                    Experience <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        id="experience"
                                        name="experience"
                                        type="text"
                                        placeholder="Experience"
                                        value="{{ old('experience') }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-heading"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="experience" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-3-desktop">
                            <x-forms.field>
                                <x-forms.label for="efp_number">
                                    EPF Number <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        id="efp_number"
                                        name="efp_number"
                                        type="text"
                                        placeholder="EPF Number"
                                        value="{{ old('efp_number') }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-heading"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="efp_number" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-3-desktop">
                            <x-forms.field>
                                <x-forms.label for="basic_salary">
                                    Basic Salary <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        id="basic_salary"
                                        name="basic_salary"
                                        type="number"
                                        placeholder="Basic Salary"
                                        value="{{ old('basic_salary') }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-dollar-sign"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="basic_salary" />
                                </x-forms.control>
                            </x-forms.field>
                        </div> 
                        <div class="column is-6-mobile is-6-tablet is-3-desktop">
                            <x-forms.field>
                                <x-forms.label for="job_type">
                                    Contract Type <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left ">
                                    <x-forms.select
                                        class="is-fullwidth"
                                        id="job_type"
                                        name="job_type"
                                    >
                                        <option
                                            disabled
                                            selected
                                        >
                                            Select Contract Type
                                        </option>
                                        <option
                                            value="permanent"
                                            @selected(old('job_type') == 'permanent')
                                        > Permanent </option>
                                        <option
                                            value="contract"
                                            @selected(old('job_type') == 'contract')
                                        > Contract </option>
                                    </x-forms.select>
                                    <x-common.icon
                                        name="fas fa-sort"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="job_type" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-3-desktop">
                            <x-forms.field>
                                <x-forms.label for="location">
                                    Location <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        id="location"
                                        name="location"
                                        type="text"
                                        placeholder="Location"
                                        value="{{ old('location') }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-map-marker-alt"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="location" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-3-desktop">
                            <x-forms.field>
                                <x-forms.label for="bank_name">
                                    Bank Name <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        id="bank_name"
                                        name="bank_name"
                                        type="text"
                                        placeholder="Bank Name"
                                        value="{{ old('bank_name') }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-bank"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="bank_name" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-3-desktop">
                            <x-forms.field>
                                <x-forms.label for="bank_account">
                                    Bank Account <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        id="bank_account"
                                        name="bank_account"
                                        type="text"
                                        placeholder="Bank Account"
                                        value="{{ old('bank_account') }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-hashtag"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="bank_account" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-3-desktop">
                            <x-forms.field>
                                <x-forms.label for="branch_name">
                                    Branch Name <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        id="branch_name"
                                        name="branch_name"
                                        type="text"
                                        placeholder="Branch Name"
                                        value="{{ old('branch_name') }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-map-marker-alt"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="branch_name" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-3-desktop">
                            <x-forms.field>
                                <x-forms.label for="tin_number">
                                    TIN Number <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        id="tin_number"
                                        name="tin_number"
                                        type="text"
                                        placeholder="TIN Number"
                                        value="{{ old('tin_number') }}"
                                        autocomplete="tin_number"
                                    />
                                    <x-common.icon
                                        name="fas fa-hashtag"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="tin_number" />
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

