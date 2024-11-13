@extends('layouts.app')

@section('title', 'Departments')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-softblue has-text-weight-medium is-size-5">
                    Departments
                    <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fas fa-sort" />
                        <span>
                            {{ number_format($totalDepartments) }} {{ str()->plural('department', $totalDepartments) }}
                        </span>
                    </span>
                </h1>
            </x-slot>
            @can('Import Department')
                <x-common.button
                    tag="button"
                    mode="button"
                    @click="$dispatch('open-import-modal') "
                    icon="fas fa-upload"
                    label="Import Departments"
                    class="btn-softblue is-outlined is-small"
                />
            @endcan
            @can('Create Department')
                <x-common.button
                    tag="a"
                    href="{{ route('departments.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Department"
                    class="btn-blue is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('deleted')" />
            <x-common.fail-message :message="count($errors->all()) ? $errors->all() : null" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
    @can('Import Department')
        <x-common.import
            title="Import Department"
            action="{{ route('departments.import') }}"
        />
    @endcan
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
