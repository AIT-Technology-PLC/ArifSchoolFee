@extends('layouts.app')

@section('title', 'Customers')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-green has-text-weight-medium is-size-5">
                    Customers
                    <span class="tag bg-green has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fas fa-user" />
                        <span>
                            {{ number_format($totalCustomers) }} {{ str()->plural('customer', $totalCustomers) }}
                        </span>
                        <span class="is-hidden-mobile">
                            &nbsp; registered
                        </span>
                    </span>
                </h1>
            </x-slot>
            @can('Create Customer')
                <x-common.button
                    tag="a"
                    href="{{ route('customers.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Customer"
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
