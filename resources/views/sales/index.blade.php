@extends('layouts.app')

@section('title', 'Invoices')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-green has-text-weight-medium is-size-5">
                    Invoices
                    <span class="tag bg-green has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fas fa-cash-register" />
                        <span>
                            {{ number_format($totalSales) }} {{ Str::plural('invoice', $totalSales) }}
                        </span>
                    </span>
                </h1>
            </x-slot>
            @can('Create Sale')
                <x-common.button
                    tag="a"
                    href="{{ route('sales.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Invoice"
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
