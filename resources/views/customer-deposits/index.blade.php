@extends('layouts.app')

@section('title', 'Deposits')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-6 p-lr-0">
            <x-common.total-model
                model="deposits"
                :amount="$totalCustomerDeposits"
                icon="fa-solid fa-sack-dollar"
            />
        </div>
        <div class="column is-6 p-lr-0">
            <x-common.index-insight
                :amount="$totalUniqueCustomers"
                border-color="#3d8660"
                text-color="text-green"
                label="Unique Customer"
            />
        </div>
        <div class="column is-6 p-lr-0">
            <x-common.index-insight
                amount="{{ number_format($totalDeposits, 2) }}"
                border-color="#86843d"
                text-color="text-gold"
                label="Total Deposit Balance (in {{ userCompany()->currency }})"
            />
        </div>
        <div class="column is-6 p-lr-0">
            <x-common.index-insight
                amount="{{ number_format($totalAvailableBalance, 2) }}"
                border-color="#86843d"
                text-color="text-gold"
                label="Available Deposit Balance (in {{ userCompany()->currency }})"
            />
        </div>
    </div>
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-green has-text-weight-medium is-size-5">
                    Deposits
                </h1>
            </x-slot>
            @can('Create Customer Deposit')
                <x-common.button
                    tag="a"
                    href="{{ route('customer-deposits.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Deposit"
                    class="btn-green is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.fail-message :message="count($errors->all()) ? $errors->all() : null" />
            <x-datatables.filter filters="'status'">
                <div class="columns is-marginless is-vcentered">
                    <div class="column is-3 p-lr-0 pt-0">
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id=""
                                    name=""
                                    class="is-size-7-mobile is-fullwidth"
                                    x-model="filters.status"
                                    x-on:change="add('status')"
                                >
                                    <option
                                        disabled
                                        selected
                                        value=""
                                    >
                                        Statuses
                                    </option>
                                    <option value="all"> All </option>
                                    @foreach (['Waiting Approval', 'Approved'] as $status)
                                        <option value="{{ str()->lower($status) }}"> {{ $status }} </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </x-datatables.filter>
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
