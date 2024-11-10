@extends('layouts.app')

@section('title', 'Create New School')

@section('content')
<x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-blue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-plus-circle" />
                    <span>
                        New School Registration
                    </span>
                </span>
            </x-slot>
        </x-content.header>
        <form
            id="formOne"
            action="{{ route('admin.schools.store') }}"
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
                                    <i class="fas fa-school"></i>
                                </span>
                            </span>
                            <div class="steps-content">
                                <p class="heading">School Information</p>
                            </div>
                        </a>
                    </li>
                    <li class="steps-segment has-gaps" data-target="step-two">
                        <a class="text-green">
                            <span class="steps-marker">
                            <span class="icon">
                                <i class="fas fa-user"></i>
                            </span>
                            </span>
                            <div class="steps-content">
                                <p class="heading">Admin Information</p>
                            </div>
                        </a>
                    </li>
                    <li class="steps-segment has-gaps" data-target="step-three">
                        <a class="text-green">
                            <span class="steps-marker">
                            <span class="icon">
                                <i class="fas fa-check-circle"></i>
                            </span>
                            </span>
                            <div class="steps-content">
                                <p class="heading">Subscription Detail</p>
                            </div>
                        </a>
                    </li>
                </ul>

                <div id="step-one" class="step-content is-active">
                    <div class="columns is-marginless is-multiline is-mobile">
                        <div class="column is-6-mobile is-6-tablet is-6-desktop">
                            <x-forms.field>
                                <x-forms.label for="company_name">
                                    Name <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        id="company_name"
                                        name="company_name"
                                        type="text"
                                        placeholder="School Name"
                                        value="{{ old('company_name') }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-school"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="company_name" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-6-desktop">
                            <x-forms.field>
                                <x-forms.label for="school_type_id">
                                    Type <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left ">
                                    <x-forms.select
                                        class="is-fullwidth"
                                        id="school_type_id"
                                        name="school_type_id"
                                    >
                                        <option
                                            disabled
                                            selected
                                        >
                                            Select Type
                                        </option>
                                        @foreach ($schoolTypes as $type)
                                            <option
                                                value="{{ $type->id }}"
                                                @selected($type->id == (old('school_type_id') ?? ''))
                                            >
                                                {{ str()->ucfirst($type->name) }}
                                            </option>
                                        @endforeach
                                    </x-forms.select>
                                    <x-common.icon
                                        name="fas fa-sort"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="school_type_id" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-forms.field>
                                <x-forms.label for="company_email">
                                    Email <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        id="company_email"
                                        name="company_email"
                                        type="company_email"
                                        placeholder="School Email Address"
                                        value="{{ old('company_email') }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-at"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="company_email" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-forms.field>
                                <x-forms.label for="company_phone">
                                    Phone <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        id="company_phone"
                                        name="company_phone"
                                        type="number"
                                        placeholder="Phone/Telephone"
                                        value="{{ old('company_phone') }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-phone"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="company_phone" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-forms.field>
                                <x-forms.label for="company_address">
                                    Address <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        id="company_address"
                                        name="company_address"
                                        type="text"
                                        placeholder="Address"
                                        value="{{ old('company_address') }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-map-marker-alt"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="company_address" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                    </div>
                </div>

                <div id="step-two" class="step-content">
                    <div class="columns is-marginless is-multiline is-mobile">
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-forms.field>
                                <x-forms.label for="name">
                                    Admin Name <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        id="name"
                                        name="name"
                                        type="text"
                                        placeholder="Admin Name"
                                        value="{{ old('name', 'Admin Support Account') }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-user"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="name" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
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
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-forms.field>
                                <x-forms.label for="phone">
                                    Phone <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        id="phone"
                                        name="phone"
                                        type="number"
                                        placeholder="Phone"
                                        value="{{ old('phone') }}"
                                        autocomplete="phone"
                                    />
                                    <x-common.icon
                                        name="fas fa-phone"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="phone" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-forms.field>
                                <x-forms.label for="address">
                                    Address <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        id="address"
                                        name="address"
                                        type="text"
                                        placeholder="Address"
                                        value="{{ old('address') }}"
                                        autocomplete="address"
                                    />
                                    <x-common.icon
                                        name="fas fa-map-marker-alt"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="address" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-4-desktop">
                            <x-forms.field>
                                <x-forms.label for="email">
                                    Admin Email <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        id="email"
                                        name="email"
                                        type="email"
                                        placeholder="example@gmail.com"
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
                        <div class="column is-12-mobile is-6-tablet is-4-desktop">
                            <x-forms.label>
                                Admin Password <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.field class="has-addons">
                                <x-forms.control class="has-icons-left is-expanded">
                                    <x-forms.input
                                        id="password"
                                        name="password"
                                        type="password"
                                        placeholder="New Password"
                                        autocomplete="new-password"
                                    />
                                    <x-common.icon
                                        name="fas fa-unlock"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="password" />
                                </x-forms.control>
                                <x-forms.control class="has-icons-left is-expanded">
                                    <x-forms.input
                                        id="password-confirm"
                                        type="password"
                                        name="password_confirmation"
                                        placeholder="Confirm Password"
                                        autocomplete="new-password"
                                    />
                                    <x-common.icon
                                        name="fas fa-unlock"
                                        class="is-small is-left"
                                    />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                    </div>
                </div>

                <div id="step-three" class="step-content">
                    <div class="columns is-marginless is-multiline is-mobile">
                        <div class="column is-6-mobile is-6-tablet is-6-desktop">
                            <x-forms.field>
                                <x-forms.label for="subscriptions[plan_id]">
                                    Subscription Plan <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.select
                                        class="is-fullwidth"
                                        id="subscriptions[plan_id]"
                                        name="subscriptions[plan_id]"
                                    >
                                        <option
                                            selected
                                            disabled
                                        >Select Subscription Plan</option>
                                        @foreach ($plans as $plan)
                                            <option
                                                value="{{ $plan->id }}"
                                                @selected($plan->id == (old('subscriptions')['plan_id'] ?? ''))
                                            >
                                                {{ str()->ucfirst($plan->name) }}
                                            </option>
                                        @endforeach
                                    </x-forms.select>
                                    <x-common.icon
                                        name="fas fa-tag"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="subscriptions.plan_id" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6-mobile is-6-tablet is-6-desktop">
                            <x-forms.label for="subscriptions[months]">
                                Subscription Period (in Months) <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.field>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        id="subscriptions[months]"
                                        name="subscriptions[months]"
                                        type="number"
                                        placeholder="Months (e.g. 12)"
                                        value="{{ old('subscriptions')['months'] ?? 12 }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-calendar"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="subscriptions.months" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        @foreach ($limits as $limit)
                            <div class="column is-6-mobile is-6-tablet is-6-desktop">
                                <x-forms.field>
                                    <x-forms.label for="limit[{{ $limit->id }}][amount]">
                                        Number of {{ str($limit->name)->title()->plural() }} <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            id="limit[{{ $limit->id }}][amount]"
                                            name="limit[{{ $limit->id }}][amount]"
                                            type="number"
                                            placeholder="Number of {{ str($limit->name)->title()->plural() }}"
                                            value="{{ old('limit')[$limit->id]['amount'] ?? '' }}"
                                        />
                                        <x-common.icon
                                            name="fas fa-cubes"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="limit.{{ $limit->id }}.amount" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                        @endforeach
                    </div>
                </div>

            </x-content.main>
            <x-content.footer>
                <div class="has-text-right">
                    <button id="prevButton" class="button bg-blue has-text-white" style="display: none;">
                        <span class="mr-2"><i class="fas fa-arrow-left"></i></span>Back
                    </button>
                    <button id="nextButton" class="button bg-blue has-text-white">
                        <span class="mr-2"><i class="fas fa-arrow-right"></i></span>Next
                    </button>
                    <button id="saveButton" class="button bg-blue has-text-white" style="display: none;">
                        <span class="mr-2"><i class="fas fa-save"></i></span>Submit
                    </button>
                </div>                    
            </x-content.footer>
        </form>  
    </x-common.content-wrapper>

    <script src="{{ asset('js/steps-component.js') }}"></script>
@endsection

