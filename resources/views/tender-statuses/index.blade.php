@extends('layouts.app')

@section('title', 'Tender Statuses')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-green has-text-weight-medium is-size-5">
                    Tender Statuses
                    <span class="tag bg-green has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fas fa-info" />
                        <span>
                            {{ number_format($totalTenderStatuses) }} {{ str()->plural('status', $totalTenderStatuses) }}
                        </span>
                    </span>
                </h1>
            </x-slot>
            @can('Import Tender')
                <x-common.button
                    tag="button"
                    mode="button"
                    @click="$dispatch('open-import-modal') "
                    icon="fas fa-upload"
                    label="Import Statuses"
                    class="btn-green is-outlined is-small"
                />
            @endcan
            @can('Create Tender')
                <x-common.button
                    tag="a"
                    href="{{ route('tender-statuses.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Status"
                    class="btn-green is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('deleted') ?? session('imported')" />
            <x-common.fail-message :message="count($errors->all()) ? $errors->all() : null" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
    @can('Import Tender')
        <x-common.import
            title="Import Statuses"
            action="{{ route('tender-statuses.import') }}"
        />
    @endcan
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
