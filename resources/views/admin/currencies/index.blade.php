@extends('layouts.app')

@section('title', 'Currency')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-softblue has-text-weight-medium is-size-5">
                    Currency
                    <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fas fa-layer-group" />
                        <span>
                            {{ number_format($totalCurrency) }} {{ str()->plural('currency'), $totalCurrency }}
                        </span>
                    </span>
                </h1>
            </x-slot>
            <x-common.button
                tag="a"
                href="{{ route('admin.currencies.create') }}"
                mode="button"
                icon="fas fa-plus-circle"
                label="Create Currency"
                class="btn-softblue is-outlined is-small"
            />
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
