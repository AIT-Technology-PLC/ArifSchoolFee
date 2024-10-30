@extends('layouts.app')

@section('title', 'Companies')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-4 p-lr-0">
            <x-common.total-model
                model="Schools"
                :amount="$companies"
                icon="fas fa-school"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                :amount="$enabledCompanies"
                border-color="#3d8660"
                text-color="text-blue"
                label="Active"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                :amount="$disabledCompanies"
                border-color="#863d63"
                text-color="text-blue"
                label="Deactivated"
            />
        </div>
    </div>

    <x-common.content-wrapper>
        <x-content.header
            title="Companies"
            is-mobile
        >
            @can('Manage Admin Panel Companies')
                <x-common.button
                    tag="a"
                    href="{{ route('admin.companies.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Company"
                    class="btn-blue is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('deleted') ?? session('imported')" />
            <x-common.fail-message :message="count($errors->all()) ? $errors->all() : null" />
            <x-datatables.filter filters="'status'">
                <div class="columns is-marginless is-vcentered">
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
                                        Status
                                    </option>
                                    <option value="all"> All </option>
                                    @foreach (['Active', 'Deactivated'] as $status)
                                        <option value="{{ str()->lower($status) }}"> {{ $status }} </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </x-datatables.filter>
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
