@extends('layouts.app')

@section('title', 'Add New Employee')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Employee" />
        <form
            id="formOne"
            action="{{ route('employees.store') }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main>
                <x-common.fail-message :message="session('limitReachedMessage')" />
                <section>
                    <div class="box radius-bottom-0 mb-0 has-background-white-bis p-3">
                        <h1 class="text-green is-size-5">
                            Basic Information
                        </h1>
                    </div>
                    <div class="box is-radiusless">
                        <div class="columns is-marginless is-multiline">
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="name">
                                        Name <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            id="name"
                                            name="name"
                                            type="text"
                                            placeholder="Employee Name"
                                            value="{{ old('name') }}"
                                            autocomplete="name"
                                            autofocus
                                        />
                                        <x-common.icon
                                            name="fas fa-user"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="name" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="email">
                                        Email <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            id="email"
                                            name="email"
                                            type="text"
                                            placeholder="Email Address"
                                            value="{{ old('email') }}"
                                            autocomplete="email"
                                        />
                                        <x-common.icon
                                            name="fas fa-at"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="email" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
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
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="phone">
                                        Phone <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            id="phone"
                                            name="phone"
                                            type="phone"
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
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="address">
                                        Address <sup class="has-text-danger">*</sup>
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
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="position">
                                        Job Title/Position <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            id="position"
                                            name="position"
                                            type="text"
                                            placeholder="Job Title"
                                            value="{{ old('position') ?? '' }}"
                                        />
                                        <x-common.icon
                                            name="fas fa-user-tie"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="position" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="job_type">
                                        Job Type <sup class="has-text-danger">*</sup>
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
                                                Select Job Type
                                            </option>
                                            <option
                                                value="full time"
                                                @selected(old('job_type') == 'full time')
                                            > Full Time </option>
                                            <option
                                                value="part time"
                                                @selected(old('job_type') == 'part time')
                                            > Part Time </option>
                                            <option
                                                value="contractual"
                                                @selected(old('job_type') == 'contractual')
                                            > Contractual </option>
                                            <option
                                                value="remote"
                                                @selected(old('job_type') == 'remote')
                                            > Remote </option>
                                            <option
                                                value="internship"
                                                @selected(old('job_type') == 'internship')
                                            > Internship </option>
                                        </x-forms.select>
                                        <x-common.icon
                                            name="fas fa-sort"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="job_type" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="warehouse_id">
                                        Assign To <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.select
                                            class="is-fullwidth"
                                            id="warehouse_id"
                                            name="warehouse_id"
                                        >
                                            @foreach ($warehouses as $warehouse)
                                                <option
                                                    value="{{ $warehouse->id }}"
                                                    {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}
                                                >{{ $warehouse->name }}</option>
                                            @endforeach
                                        </x-forms.select>
                                        <x-common.icon
                                            name="fas fa-warehouse"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="warehouse_id" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.label>
                                    Password <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.field class="has-addons">
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            id="password"
                                            name="password"
                                            type="password"
                                            placeholder="Password"
                                            autocomplete="new-password"
                                        />
                                        <x-common.icon
                                            name="fas fa-unlock"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="password" />
                                    </x-forms.control>
                                    <x-forms.control class="has-icons-left">
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
                                        <x-common.validation-error property="password" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.label>
                                    ID Details <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.field class="has-addons">
                                    <x-forms.control class="has-icons-left ">
                                        <x-forms.select
                                            class="is-fullwidth"
                                            id="id_type"
                                            name="id_type"
                                        >
                                            <option
                                                disabled
                                                selected
                                            >
                                                Select ID Type
                                            </option>
                                            <option
                                                value="passport"
                                                @selected(old('id_type') == 'passport')
                                            > Passport </option>
                                            <option
                                                value="drivers license"
                                                @selected(old('id_type') == 'drivers license')
                                            > Drivers license </option>
                                            <option
                                                value="employee id"
                                                @selected(old('id_type') == 'employee id')
                                            > Employee ID </option>
                                            <option
                                                value="kebele id"
                                                @selected(old('id_type') == 'kebele id')
                                            > Kebele ID </option>
                                            <option
                                                value="student id"
                                                @selected(old('id_type') == 'student id')
                                            > Student ID </option>
                                        </x-forms.select>
                                        <x-common.icon
                                            name="fas fa-id-card"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="id_type" />
                                    </x-forms.control>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            id="id_number"
                                            name="id_number"
                                            type="text"
                                            placeholder="ID Number"
                                            value="{{ old('id_number') }}"
                                            autocomplete="id_number"
                                        />
                                        <x-common.icon
                                            name="fas fa-hashtag"
                                            class="is-small is-left"
                                        />
                                        <x-common.validation-error property="id_number" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="enabled">
                                        Can this employee access the system? <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.control>
                                        <label class="radio has-text-grey has-text-weight-light">
                                            <input
                                                type="radio"
                                                name="enabled"
                                                value="1"
                                                class="mt-3"
                                                {{ old('enabled') == 1 ? 'checked' : '' }}
                                            >
                                            Yes, this employee can access the system
                                        </label>
                                        <br>
                                        <label class="radio has-text-grey has-text-weight-light mt-2">
                                            <input
                                                type="radio"
                                                name="enabled"
                                                value="0"
                                                {{ old('enabled') == 0 ? 'checked' : '' }}
                                            >
                                            No, this employee can't access the system
                                        </label>
                                        <x-common.validation-error property="enabled" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                            <div class="column is-6">
                                <x-forms.field>
                                    <x-forms.label for="role">
                                        Choose Role <sup class="has-text-danger">*</sup>
                                    </x-forms.label>
                                    <x-forms.control>
                                        @foreach ($roles as $role)
                                            <label class="radio has-text-grey has-text-weight-light">
                                                <input
                                                    type="radio"
                                                    name="role"
                                                    value="{{ $role->name }}"
                                                    class="mt-3"
                                                    {{ old('role') == $role->name ? 'checked' : '' }}
                                                >
                                                {{ $role->name }}
                                            </label>
                                            <br>
                                        @endforeach
                                        <x-common.validation-error property="role" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mt-5">
                    <div class="box radius-bottom-0 mb-0 has-background-white-bis p-3">
                        <h1 class="text-green is-size-5">
                            Branch Permissions
                        </h1>
                    </div>
                    <div class="box is-radiusless">
                        <div class="columns is-marginless is-multiline">
                            <div class="column is-3">
                                <label
                                    for="transactions[]"
                                    class="label text-green"
                                > Transactions <sup class="has-text-danger"></sup> </label>
                                <x-forms.field>
                                    @foreach ($warehouses as $warehouse)
                                        <label class="checkbox mr-3 has-text-grey has-text-weight-light">
                                            <input
                                                name="transactions[]"
                                                value="{{ $warehouse->id }}"
                                                type="checkbox"
                                                {{ in_array($warehouse->id, old('transactions', [])) ? 'checked' : '' }}
                                            >
                                            {{ $warehouse->name }}
                                        </label>
                                        <br>
                                    @endforeach
                                </x-forms.field>
                                <x-common.validation-error property="transactions.*" />
                            </div>
                        </div>
                    </div>
                </section>
            </x-content.main>
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection
