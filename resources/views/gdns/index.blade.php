@extends('layouts.app')

@section('title')
    Delivery Order Management
@endsection

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-6 p-lr-0">
            <div class="box text-green">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column has-text-centered is-paddingless">
                        <span class="icon is-large is-size-1">
                            <i class="fas fa-file-invoice"></i>
                        </span>
                    </div>
                    <div class="column is-paddingless">
                        <div class="is-size-3 has-text-weight-bold">
                            {{ $totalGdns }}
                        </div>
                        <div class="is-size-7">
                            TOTAL DELIVERY ORDERS
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="column is-6 p-lr-0">
            <div class="box text-purple">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column is-paddingless has-text-centered">
                        <div class="is-uppercase is-size-7">
                            Create new Delivery Order for new sales
                        </div>
                        <div class="is-size-3">
                            <a
                                href="{{ route('gdns.create') }}"
                                class="button bg-purple has-text-white has-text-weight-medium is-size-7 px-5 py-4 mt-3"
                            >
                                <span class="icon">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span>
                                    Create New DO
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="column is-4 p-lr-0">
            <div
                class="box text-green has-text-centered"
                style="border-left: 2px solid #3d8660;"
            >
                <div class="is-size-3 has-text-weight-bold">
                    {{ $totalSubtracted }}
                </div>
                <div class="is-uppercase is-size-7">
                    Subtracted
                </div>
            </div>
        </div>
        <div class="column is-4 p-lr-0">
            <div
                class="box text-gold has-text-centered"
                style="border-left: 2px solid #86843d;"
            >
                <div class="is-size-3 has-text-weight-bold">
                    {{ $totalNotSubtracted }}
                </div>
                <div class="is-uppercase is-size-7">
                    Approved (not Subtracted)
                </div>
            </div>
        </div>
        <div class="column is-4 p-lr-0">
            <div
                class="box text-purple has-text-centered"
                style="border-left: 2px solid #863d63;"
            >
                <div class="is-size-3 has-text-weight-bold">
                    {{ $totalNotApproved }}
                </div>
                <div class="is-uppercase is-size-7">
                    Waiting Approval
                </div>
            </div>
        </div>
    </div>
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                Delivery Order Management
            </h1>
        </div>
        <div class="box radius-top-0">
            <x-common.success-message :message="session('deleted')" />
            <div>
                {{ $dataTable->table() }}
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
