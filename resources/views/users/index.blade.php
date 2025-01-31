@extends('layouts.app')

@section('title', 'User Account')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-4 p-lr-0">
            <x-common.total-model
                model="Users"
                :amount="$totalEmployees"
                icon="fas fa-user-tie"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.total-model
                model="Enabled"
                box-color="bg-lightblue"
                :amount="$totalEnabledEmployees"
                icon="fas fa-check"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.total-model
                model="Disabled"
                box-color="bg-red"
                :amount="$totalBlockedEmployees"
                icon="fas fa-ban"
            />
        </div>
    </div>

    <x-common.content-wrapper>
        <x-content.header title="Users">
            @can('Import Employee')
                <x-common.button
                    tag="button"
                    mode="button"
                    @click="$dispatch('open-import-modal')"
                    icon="fas fa-upload"
                    label="Import Users"
                    class="btn-softblue is-outlined is-small"
                />
            @endcan
            @can('Create Employee')
                <x-common.button
                    tag="a"
                    href="{{ route('users.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create User"
                    class="btn-softblue is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('successMessage') ?? session('deleted') ?? session('imported')" />
            <x-common.fail-message :message="session('limitReachedMessage') ?? (count($errors->all()) ? $errors->all() : null)" />
            <x-datatables.filter filters="'branch', 'status'">
                <div class="columns is-marginless is-vcentered">
                    @can('Read Employee')
                        <div class="column is-3 p-lr-0 pt-0">
                            <x-forms.field class="has-text-centered">
                                <x-forms.control>
                                    <x-forms.select
                                        id=""
                                        name=""
                                        class="is-size-7-mobile is-fullwidth"
                                        x-model="filters.branch"
                                        x-on:change="add('branch')"
                                    >
                                        <option
                                            disabled
                                            selected
                                            value=""
                                        >
                                            Branches
                                        </option>
                                        <option value="all"> All </option>
                                        @foreach ($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}"> {{ $warehouse->name }} </option>
                                        @endforeach
                                    </x-forms.select>
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                    @endcan
                    <div class="column is-3 p-lr-0 pt-0">
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id=""
                                    name=""
                                    class="is-size-7-mobile is-fullwidth"
                                    x-model="filters.status"
                                    x-on:change="add('status')"
                                >
                                    <option
                                        disabled
                                        selected
                                        value=""
                                    >
                                        Statuses
                                    </option>
                                    <option value="all"> All </option>
                                    @foreach (['Enabled', 'Disabled'] as $status)
                                        <option value="{{ str()->lower($status) }}"> {{ $status }} </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </x-datatables.filter>
            <div>
                {{ $dataTable->table() }}
            </div>
        </x-content.footer>
    </x-common.content-wrapper>
    @can('Import Employee')
        <x-common.import
            title="Import Users"
            action="{{ route('users.import') }}"
        />
    @endcan
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
