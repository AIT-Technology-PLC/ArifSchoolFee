@extends('layouts.app')

@section('title', 'Edit Admin')

@section('content')
    <x-common.content-wrapper x-data="usersInformation(
        '{{ $user->user_type }}')">
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-pen" />
                    <span>
                        Edit Admin
                    </span>
                </span>
            </x-slot>
        </x-content.header>
        <form
            id="formOne"
            action="{{ route('admin.users.update', $user->id) }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            @method('PATCH')
            <x-content.main>
                <div class="columns is-marginless is-multiline">
                    <div class="column is-4">
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
                                    value="{{ old('name') ?? $user->name }}"
                                />
                                <x-common.icon
                                    name="fas fa-user"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="name" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
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
                                    value="{{ old('email') ?? $user->email }}"
                                />
                                <x-common.icon
                                    name="fas fa-at"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="email" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-4">
                        <x-forms.field>
                            <x-forms.label for="user_type">
                                User Type <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left ">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="user_type"
                                    name="user_type"
                                    x-model="userType"
                                >
                                    <option
                                        disabled
                                        selected
                                    >
                                        Select Type
                                    </option>
                                    <option
                                        value="admin"
                                        @selected(old('user_type', $user->user_type) == 'admin')
                                    >
                                        Admin
                                    </option>
                                    <option
                                        value="call_center"
                                        @selected(old('user_type', $user->user_type) == 'call_center')
                                    >
                                        Call Center
                                    <option
                                        value="bank"
                                        @selected(old('user_type', $user->user_type) == 'bank')
                                    >
                                        Bank
                                    </option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="user_type" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div 
                        class="column is-6"
                        x-show="isUserTypeBankSelected()"
                        >
                        <x-forms.label>
                            Bank Details <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="bank_name"
                                    name="bank_name"
                                >
                                    <option
                                        disabled
                                        selected
                                    >
                                        Select Bank
                                    </option>
                                    @if (old('bank_name', $user->bank_name))
                                        <option
                                            value="{{ old('bank_name', $user->bank_name) }}"
                                            selected
                                        >
                                            {{ old('bank_name', $user->bank_name) }}
                                        </option>
                                    @endif
                                    @include('lists.banks')
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="bank_name" />
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
