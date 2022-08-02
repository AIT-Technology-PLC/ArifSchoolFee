@extends('layouts.app')

@section('title', 'Compensation')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-green has-text-weight-medium is-size-5">
                    Compensation
                    <span class="tag bg-green has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fa-solid fa-circle-dollar-to-slot" />
                        <span>
                            {{ number_format($totalCompensations) }} {{ str()->plural('compensation', $totalCompensations) }}
                        </span>
                    </span>
                </h1>
            </x-slot>
            @can('Create Compensation')
                <x-common.button
                    tag="a"
                    href="{{ route('compensations.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Compensation"
                    class="btn-green is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('deleted')" />
            <x-common.fail-message :message="session('failedMessage')" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
