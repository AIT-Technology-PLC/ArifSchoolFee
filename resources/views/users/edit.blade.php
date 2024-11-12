@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-pen" />
                    <span>
                        Edit User
                    </span>
                </span>
            </x-slot>
        </x-content.header>
        <form
            id="formOne"
            action="{{ route('users.update', $user->id) }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            @method('PATCH')
            <x-content.main>
                <x-common.fail-message :message="session('limitReachedMessage')" />
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6-mobile is-4-tablet is-4-desktop">
                        <x-forms.field>
                            <x-forms.label for="name">
                                Name <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="name"
                                    name="name"
                                    type="text"
                                    placeholder="User Name"
                                    value="{{ $user->user->name }}"
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
                    <div class="column is-6-mobile is-4-tablet is-4-desktop">
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
                                    value="{{ $user->user->email }}"
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
                    <div class="column is-6-mobile is-4-tablet is-4-desktop">
                        <x-forms.label>
                            Change Password <sup class="has-text-danger"></sup>
                        </x-forms.label>
                        <x-forms.field class="has-addons">
                            <x-forms.control class="has-icons-left">
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
                    <div class="column is-6-mobile is-4-tablet is-4-desktop">
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
                                        @selected(old('gender', $user->gender) == 'male')
                                    > Male</option>
                                    <option
                                        value="female"
                                        @selected(old('gender', $user->gender) == 'female')
                                    > Female </option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="gender" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6-mobile is-4-tablet is-4-desktop">
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
                                    value="{{ old('phone', $user->phone) }}"
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
                    <div class="column is-6-mobile is-4-tablet is-4-desktop">
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
                                    value="{{ old('address', $user->address) }}"
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
                    <div class="column is-6-mobile is-4-tablet is-4-desktop">
                        <x-forms.field>
                            <x-forms.label for="warehouse_id">
                                Branch <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="warehouse_id"
                                    name="warehouse_id"
                                >
                                    @foreach ($branches as $branch)
                                        <option
                                            value="{{ $branch->id }}"
                                            @selected(old('warehouse_id', $user->user->warehouse_id) == $branch->id)
                                        >
                                            {{ $branch->name }}
                                        </option>
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
                    <div class="column is-6-mobile is-4-tablet is-4-desktop">
                        <x-forms.field>
                            <x-forms.label for="position">
                                Position <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="position"
                                    name="position"
                                    type="text"
                                    placeholder="User position"
                                    value="{{ $user->position }}"
                                />
                                <x-common.icon
                                    name="fas fa-user-tie"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="position" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    @if (authUser()->id != $user->user->id && !$user->user->hasRole('System Manager'))
                        <div class="column is-6-mobile is-4-tablet is-4-desktop">
                            <x-forms.field>
                                <x-forms.label for="role">
                                    Choose Role <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left">
                                        <x-forms.select
                                            class="is-fullwidth"
                                            id="role"
                                            name="role"
                                        >
                                            <option 
                                                selected
                                                disabled
                                            >Select Role</option>
                                            @foreach ($roles as $role)
                                                    <option
                                                        value="{{ $role->name }}"
                                                        {{ old('role') == $role->name ? 'selected' : '' }}
                                                    >
                                                        {{ $role->name }}
                                                    </option>
                                            @endforeach
                                        </x-forms.select>
                                        <x-common.icon
                                            name="fas fa-layer-group"
                                            class="is-small is-left"
                                        />
                                    <x-common.validation-error property="role" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                    @endif
                    @if (authUser()->id != $user->user->id && !$user->user->hasRole('System Manager'))
                        <div class="column is-4">
                            <x-forms.field>
                                <x-forms.label for="enabled">
                                    Can this user access the system? <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control>
                                    <label class="radio has-text-grey has-text-weight-light">
                                        <input
                                            type="radio"
                                            name="enabled"
                                            value="1"
                                            class="mt-3"
                                            @checked(old('enabled', $user->enabled))
                                        >
                                        Yes, this user can access the system
                                    </label>
                                    <br>
                                    <label class="radio has-text-grey has-text-weight-light mt-2">
                                        <input
                                            type="radio"
                                            name="enabled"
                                            value="0"
                                            @checked(!old('enabled', $user->enabled))
                                        >
                                        No, this user can't access the system
                                    </label>
                                    <x-common.validation-error property="enabled" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                    @endif
                    @if (authUser()->id != $user->user->id && !$user->user->hasRole('System Manager'))
                        <div class="column is-12">
                            <x-forms.label
                                    for="transactions[]"
                                    class="label text-green"
                                > Branch Permissions - Transaction <sup class="has-text-danger"></sup> 
                            </x-forms.label>
                            <x-forms.field>
                                @foreach ($branches as $branch)
                                    <label class="checkbox mr-3 has-text-grey has-text-weight-light">
                                        <input
                                            name="transactions[]"
                                            value="{{ $branch->id }}"
                                            type="checkbox"
                                            {{ isset($branchPermissions['transactions']) ? ($branchPermissions['transactions']->contains($branch) ? 'checked' : '') : '' }}
                                        >
                                        {{ $branch->name }}
                                    </label>
                                    <br>
                                @endforeach
                            </x-forms.field>
                            <x-common.validation-error property="transactions.*" />
                        </div>
                    @endif
                </div>
            </x-content.main>
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection
