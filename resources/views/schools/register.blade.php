@extends('auth.layout.auth')

@section('title')
    Subscription - {{ env('APP_NAME' ?? null) }}
@endsection

@section('content')

    <div class="container-login is-12">
        <div class="column is-9 p-0">
            <div class="columns is-multiline">
                <div class="column is-4 login login-img" style="background-color:#6079ca">
                    <img class="" src="{{ asset('img/registration-image.png')}}">
                </div>

                <div class="column is-8 login">
                    <section class="section">
                        <div class="has-text-centered">
                            <img class="register-logo" src="{{ asset('img/AIT LOGO.png')}}">
                        </div>

                        <form id="formOne"
                            method="POST"
                            action="{{ route('schools.store') }}"
                            novalidate
                            autocomplete="off"
                        >
                            @csrf
                            <x-common.success-message :message="session('successMessage')" />
                            <x-common.fail-message :message="session('failedMessage')" />

                            <ul class="steps is-horizontal is-medium is-centered has-content-centered">
                                <li class="steps-segment is-active has-gaps" data-target="step-one">
                                    <a class="text-green">
                                        <span class="steps-marker">
                                            <span class="icon">
                                                <i class="fas fa-graduation-cap"></i>
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
                                <li class="steps-segment has-gaps" data-target="step-last">
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
                                                    name="fas fa-graduation-cap"
                                                    class="is-small is-left"
                                                />
                                                <x-common.validation-error property="company_name" />
                                            </x-forms.control>
                                        </x-forms.field>
                                    </div>
                                    <div class="column is-6-mobile is-6-tablet is-6-desktop">
                                        <x-forms.field>
                                            <x-forms.label for="company_code">
                                                School Code <sup class="has-text-danger">*</sup>
                                            </x-forms.label>
                                            <x-forms.control class="has-icons-left">
                                                <x-forms.input
                                                    type="text"
                                                    id="company_code"
                                                    name="company_code"
                                                    placeholder="Ex. SCM"
                                                    value="{{ old('company_code') }}"
                                                />
                                                <x-common.icon
                                                    name="fa-brands fa-autoprefixer"
                                                    class="is-small is-left"
                                                />
                                                <x-common.validation-error property="company_code" />
                                            </x-forms.control>
                                            <p class="help is-danger">
                                                Once this code is saved, it cannot be edited!
                                            </p>
                                        </x-forms.field>
                                    </div>
                                    <div class="column is-6-mobile is-6-tablet is-6-desktop">
                                        <x-forms.field>
                                            <x-forms.label for="school_type_id">
                                                Type <sup class="has-text-danger">*</sup>
                                            </x-forms.label>
                                            <x-forms.control class="has-icons-left ">
                                                <x-forms.select
                                                    class="is-rounded is-fullwidth"
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
                                    <div class="column is-6-mobile is-6-tablet is-6-desktop">
                                        <x-forms.field>
                                            <x-forms.label for="email">
                                                Email <sup class="has-text-danger"></sup>
                                            </x-forms.label>
                                            <x-forms.control class="has-icons-left">
                                                <x-forms.input
                                                    id="email"
                                                    name="email"
                                                    type="email"
                                                    placeholder="School Email Address"
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
                                    <div class="column is-6-mobile is-6-tablet is-6-desktop">
                                        <x-forms.field>
                                            <x-forms.label for="phone">
                                                Phone <sup class="has-text-danger"></sup>
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
                                    <div class="column is-6-mobile is-6-tablet is-6-desktop">
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
                                                />
                                                <x-common.icon
                                                    name="fas fa-map-marker-alt"
                                                    class="is-small is-left"
                                                />
                                                <x-common.validation-error property="address" />
                                            </x-forms.control>
                                        </x-forms.field>
                                    </div>
                                </div>
                            </div>
            
                            <div id="step-two" class="step-content">
                                <div class="columns is-marginless is-multiline is-mobile">
                                    <div class="column is-6-mobile is-6-tablet is-6-desktop">
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
                                    <div class="column is-6-mobile is-6-tablet is-6-desktop">
                                        <x-forms.field>
                                            <x-forms.label for="gender">
                                                Gender <sup class="has-text-danger">*</sup>
                                            </x-forms.label>
                                            <x-forms.control class="has-icons-left ">
                                                <x-forms.select
                                                    class="is-rounded is-fullwidth"
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
                                    <div class="column is-6-mobile is-6-tablet is-6-desktop">
                                        <x-forms.field>
                                            <x-forms.label for="user.phone">
                                                Phone <sup class="has-text-danger">*</sup>
                                            </x-forms.label>
                                            <x-forms.control class="has-icons-left">
                                                <x-forms.input
                                                    id="user.phone"
                                                    name="user[phone]"
                                                    type="number"
                                                    placeholder="Phone"
                                                    value="{{ old('user.phone') }}"
                                                    autocomplete="phone"
                                                />
                                                <x-common.icon
                                                    name="fas fa-phone"
                                                    class="is-small is-left"
                                                />
                                                <x-common.validation-error property="user.phone" />
                                            </x-forms.control>
                                        </x-forms.field>
                                    </div>
                                    <div class="column is-6-mobile is-6-tablet is-6-desktop">
                                        <x-forms.field>
                                            <x-forms.label for="user.email">
                                                Admin Email <sup class="has-text-danger">*</sup>
                                            </x-forms.label>
                                            <x-forms.control class="has-icons-left">
                                                <x-forms.input
                                                    id="user.email"
                                                    name="user[email]"
                                                    type="email"
                                                    placeholder="example@gmail.com"
                                                    value="{{ old('user.email') }}"
                                                />
                                                <x-common.icon
                                                    name="fas fa-at"
                                                    class="is-small is-left"
                                                />
                                                <x-common.validation-error property="user.email" />
                                            </x-forms.control>
                                        </x-forms.field>
                                    </div>
                                    <div class="column is-6-mobile is-6-tablet is-6-desktop">
                                        <x-forms.field>
                                            <x-forms.label for="user.address">
                                                Address <sup class="has-text-danger"></sup>
                                            </x-forms.label>
                                            <x-forms.control class="has-icons-left">
                                                <x-forms.input
                                                    id="user.address"
                                                    name="user[address]"
                                                    type="text"
                                                    placeholder="Address"
                                                    value="{{ old('user.address') }}"
                                                    autocomplete="address"
                                                />
                                                <x-common.icon
                                                    name="fas fa-map-marker-alt"
                                                    class="is-small is-left"
                                                />
                                                <x-common.validation-error property="user.address" />
                                            </x-forms.control>
                                        </x-forms.field>
                                    </div>
                                    <div class="column is-6-mobile is-6-tablet is-6-desktop">
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
                                                    id="charge_amount"
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
            
                            <div id="step-last" class="step-content">
                                <div class="columns is-marginless is-multiline is-mobile">
                                    <div class="column is-6-mobile is-6-tablet is-6-desktop">
                                        <x-forms.field>
                                            <x-forms.label for="subscriptions[plan_id]">
                                                Subscription Plan <sup class="has-text-danger">*</sup>
                                            </x-forms.label>
                                            <x-forms.control class="has-icons-left">
                                                <x-forms.select
                                                    class="is-rounded is-fullwidth"
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

                            <div class="has-text-right mb-2">
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
                        </form>
                        
                        <div class="has-text-centered is-size-7">
                            <p class="has-text-grey">
                                Copyright © {{ now()->year }} Powered by AIT Technology.
                            </p>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

<script src="{{ asset('js/steps-component.js') }}"></script>
@endsection