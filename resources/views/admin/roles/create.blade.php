@extends('layouts.app')

@section('title', 'Create New Role')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-blue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-plus-circle" />
                    <span>
                        New Role
                    </span>
                </span>
            </x-slot>
        </x-content.header>
        <form
            id="formOne"
            action="{{ route('admin.roles.store') }}"
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
                                    placeholder="Role Name"
                                    value="{{ old('name') }}"
                                />
                                <x-common.icon
                                    name="fas fa-heading"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="name" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.field>
                            <x-forms.label for="guard_name">
                                Guard Name <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    id="guard_name"
                                    name="guard_name"
                                    type="text"
                                    value="{{'web'}}"
                                />
                                <x-common.icon
                                    name="fas fa-heading"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="guard_name" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
                
                <div class="columns is-marginless is-multiline">
                    @foreach ($permissionCategories as $key => $value)
                        @continue(!isFeatureEnabled($value['feature']))
                        <div
                            class="column is-6"
                            x-init="addToPermissionList('{{ str($value['label'])->remove(' ')->lower() }}')"
                            x-ref="{{ str($value['label'])->remove(' ')->lower() }}"
                        >
                            <div class="message">
                                <div class="message-header has-background-white-ter text-green">
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
                </div>
            </x-content.main>
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection
