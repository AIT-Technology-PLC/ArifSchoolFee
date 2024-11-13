@extends('layouts.app')

@section('title', 'Designations')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-softblue has-text-weight-medium is-size-5">
                    Designations
                    <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fas fa-sort" />
                        <span>
                            {{ number_format($totalDesignations) }} {{ str()->plural('department', $totalDesignations) }}
                        </span>
                    </span>
                </h1>
            </x-slot>
            @can('Import Designation')
                <x-common.button
                    tag="button"
                    mode="button"
                    @click="$dispatch('open-import-modal') "
                    icon="fas fa-upload"
                    label="Import Designations"
                    class="btn-softblue is-outlined is-small"
                />
            @endcan
            @can('Create Designation')
                <x-common.button
                    tag="a"
                    href="{{ route('designations.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Designation"
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
    @can('Import Designation')
        <x-common.import
            title="Import Designation"
            action="{{ route('designations.import') }}"
        />
    @endcan
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
