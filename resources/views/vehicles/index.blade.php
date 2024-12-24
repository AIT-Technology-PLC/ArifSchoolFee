@extends('layouts.app')

@section('title', 'Vehicle')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-softblue has-text-weight-medium is-size-5">
                    Vehicle
                    <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fas fa-layer-group" />
                        <span>
                            {{ number_format($totalVehicles) }} {{ str()->plural('vehicle'), $totalVehicles }}
                        </span>
                    </span>
                </h1>
            </x-slot>
            @can('Import Vehicle')
                <x-common.button
                    tag="button"
                    mode="button"
                    @click="$dispatch('open-import-modal') "
                    icon="fas fa-upload"
                    label="Import Vehicle"
                    class="btn-softblue is-outlined is-small"
                />
            @endcan
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
            <x-common.success-message :message="session('successMessage') ?? session('deleted') ?? session('imported')" />
            <x-common.fail-message :message="session('failedMessage') ?? (count($errors->all()) ? $errors->all() : null)" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
    @can('Import Vehicle')
        <x-common.import
            title="Import Vehicle"
            action="{{ route('vehicles.import') }}"
        />
    @endcan
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
