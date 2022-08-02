@extends('layouts.app')

@section('title', 'Company Compensation')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-green has-text-weight-medium is-size-5">
                    Company Compensations
                    <span class="tag bg-green has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fas fa-users-rectangle" />
                        <span>
                            {{ number_format($totalCompensations) }} {{ str()->plural('company compensation', $totalCompensations) }}
                        </span>
                    </span>
                </h1>
            </x-slot>
            @can('Create Company Compensation')
                <x-common.button
                    tag="a"
                    href="{{ route('company-compensations.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Company Compensation"
                    class="btn-green is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('deleted')" />
            <x-common.fail-message :message="count($errors->all()) ? $errors->all() : null" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
