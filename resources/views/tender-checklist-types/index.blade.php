@extends('layouts.app')

@section('title', 'Tender Checklist Types')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-green has-text-weight-medium is-size-5">
                    Tender Checklist Types
                    <span class="tag bg-green has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fas fa-tasks" />
                        <span>
                            {{ number_format($totalTenderChecklistTypes) }} {{ str()->plural('checklist type', $totalTenderChecklistTypes) }}
                        </span>
                    </span>
                </h1>
            </x-slot>
            @can('Create Tender')
                <x-common.button
                    tag="a"
                    href="{{ route('tender-checklist-types.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Checklist Type"
                    class="btn-green is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('deleted')" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
