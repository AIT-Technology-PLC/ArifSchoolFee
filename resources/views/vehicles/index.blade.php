@extends('layouts.app')

@section('title', 'Vehicle')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-softblue has-text-weight-medium is-size-5">
                    Vehicle
                    <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fas fa-sort" />
                        <span>
                            {{ number_format($totalVehicles) }} {{ str()->plural('vehicle'), $totalVehicles }}
                        </span>
                    </span>
                </h1>
            </x-slot>
            @can('Create Vehicle')
                <x-common.button
                    tag="a"
                    href="{{ route('vehicles.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Vehicle"
                    class="btn-softblue is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('successMessage')" />
            <x-common.fail-message :message="session('failedMessage')" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
