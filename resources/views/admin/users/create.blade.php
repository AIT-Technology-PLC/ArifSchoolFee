@extends('layouts.app')

@section('title', 'Create New User')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New User" />
        <form
            id="formOne"
            action="{{ route('admin.users.store') }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            <x-content.main>
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
                                    placeholder="Admin Name"
                                    value="{{ old('name') }}"
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
                                    type="email"
                                    placeholder="Admin Email"
                                    value="{{ old('email', '@onrica-ap.com') }}"
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
                        <x-forms.label>
                            Password <sup class="has-text-danger">*</sup>
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
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="permissions[]">
                                Permissions <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    x-init="initializeSelect2($el, '')"
                                    class="is-fullwidth is-multiple"
                                    id="permissions[]"
                                    name="permissions[]"
                                    multiple
                                    style="width: 100% !important"
                                >
                                    @foreach ($permissions as $permission)
                                        <option
                                            value="{{ $permission->name }}"
                                            @selected(in_array($permission->name, old('permissions', [])))
                                        >
                                            {{ $permission->name }}
                                        </option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.validation-error property="permissions" />
                                <x-common.validation-error property="permissions.*" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </x-content.main>
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection
