@extends('layouts.app')

@section('title', 'Route')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-softblue has-text-weight-medium is-size-5">
                    Route
                    <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fas fa-sort" />
                        <span>
                            {{ number_format($totalRoutes) }} {{ str()->plural('route'), $totalRoutes }}
                        </span>
                    </span>
                </h1>
            </x-slot>
            @can('Import Route')
                <x-common.button
                    tag="button"
                    mode="button"
                    @click="$dispatch('open-import-modal') "
                    icon="fas fa-upload"
                    label="Import Route"
                    class="btn-softblue is-outlined is-small"
                />
            @endcan
            @can('Create Route')
                <x-common.button
                    tag="a"
                    href="{{ route('routes.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Route"
                    class="btn-softblue is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('successMessage') ?? session('deleted') ?? session('imported')" />
            <x-common.fail-message :message="session('failedMessage') ?? (count($errors->all()) ? $errors->all() : null)" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
    @can('Import Route')
        <x-common.import
            title="Import Route"
            action="{{ route('routes.import') }}"
        />
    @endcan
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
