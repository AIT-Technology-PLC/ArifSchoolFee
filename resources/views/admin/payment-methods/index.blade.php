@extends('layouts.app')

@section('title', 'Payment Method')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-softblue has-text-weight-medium is-size-5">
                    Payment Method
                    <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fas fa-layer-group" />
                        <span>
                            {{ number_format($totalMethods) }} {{ str()->plural('method'), $totalMethods }}
                        </span>
                    </span>
                </h1>
            </x-slot>
            <x-common.button
                tag="a"
                href="{{ route('admin.payment-methods.create') }}"
                mode="button"
                icon="fas fa-plus-circle"
                label="Create Payment Method"
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
