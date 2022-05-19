@extends('layouts.app')

@section('title')
    Permission Management
@endsection

@section('content')
    <x-common.content-wrapper x-data="permissionFilter">
        <x-content.header title="Permission Management - {{ $employee->user->name }}">
            <input
                autofocus
                type="input"
                class="input is-small"
                placeholder="Search"
                x-model="searchQuery"
                @keyup="filterPermissions"
            >
        </x-content.header>
        <form
            id="formOne"
            action="{{ route('permissions.update', $employee->id) }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            @method('PATCH')
            <x-content.main>
                <x-common.success-message :message="session('message')" />
                <div class="columns is-marginless is-multiline">
                    @foreach ($permissionCategories as $key => $value)
                        @continue(!isFeatureEnabled($value['feature']))
                        <div
                            class="column is-6"
                            x-init="addToPermissionList('{{ str($value['label'])->remove(' ')->lower() }}')"
                            x-ref="{{ str($value['label'])->remove(' ')->lower() }}"
                        >
                            <div class="message">
                                <div class="message-header has-background-white-ter text-gold">
                                    {{ $value['label'] }} Permissions
                                </div>
                                <div class="message-body has-background-white-bis">
                                    <div class="columns is-marginless is-multiline">
                                        @foreach ($permissionsByCategories[$key] as $permission)
                                            <div class="column is-one-third">
                                                <div class="field">
                                                    <div class="control">
                                                        <label class="checkbox text-green has-text-weight-normal is-size-7">
                                                            <input
                                                                type="checkbox"
                                                                name="permissions[]"
                                                                value="{{ $permission }}"
                                                                {{ $userDirectPermissions->contains($permission) ? 'checked' : '' }}
                                                            >
                                                            {{ $permission }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @foreach ($pads as $pad)
                        @continue(!$pad->isEnabled())
                        <div
                            class="column is-6"
                            x-init="addToPermissionList('{{ str($pad->name)->remove(' ')->lower() }}')"
                            x-ref="{{ str($pad->name)->remove(' ')->lower() }}"
                        >
                            <div class="message">
                                <div class="message-header has-background-white-ter text-gold">
                                    {{ $pad->name }} Permissions
                                </div>
                                <div class="message-body has-background-white-bis">
                                    <div class="columns is-marginless is-multiline">
                                        @foreach ($pad->padPermissions as $padPermission)
                                            <div class="column is-one-third">
                                                <div class="field">
                                                    <div class="control">
                                                        <label class="checkbox text-green has-text-weight-normal is-size-7">
                                                            <input
                                                                type="checkbox"
                                                                name="padPermissions[]"
                                                                value="{{ $padPermission->id }}"
                                                                {{ $userPadPermissions->contains($padPermission->id) ? 'checked' : '' }}
                                                            >
                                                            {{ $padPermission->name }} {{ $padPermission->pad->name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-content.main>
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection
