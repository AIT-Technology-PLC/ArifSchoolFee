@extends('layouts.app')

@section('title', 'Customers')

@section('content')
    <div class="columns is-marginless">
        <div class="column is-6">
            <div class="box text-green">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column has-text-centered is-paddingless">
                        <span class="icon is-large is-size-1">
                            <i class="fas fa-address-card"></i>
                        </span>
                    </div>
                    <div class="column is-paddingless">
                        <div class="is-size-3 has-text-weight-bold">
                            {{ $totalCustomers }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            Total Customers
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="column is-6">
            <div class="box text-purple">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column is-paddingless has-text-centered">
                        <div class="is-uppercase is-size-7">
                            Add new customer and assign products to them
                        </div>
                        <div class="is-size-3">
                            <a
                                href="{{ route('customers.create') }}"
                                class="button bg-purple has-text-white has-text-weight-medium is-size-7 px-5 py-4 mt-3"
                            >
                                <span class="icon">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span>
                                    Create New Customer
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                Customers
            </h1>
        </div>
        <div class="box radius-top-0">
            <x-common.success-message :message="session('deleted')" />
            {{ $dataTable->table() }}
        </div>
    </section>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
