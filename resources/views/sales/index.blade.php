@extends('layouts.app')

@section('title', 'Invoices')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-3 p-lr-0">
            <x-common.total-model
                model="Invoices"
                :amount="$totalSales"
                icon="fas fa-cash-register"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                :amount="$totalSubtracted"
                border-color="#3d8660"
                text-color="text-green"
                label="Subtracted"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                :amount="$totalApproved"
                border-color="#86843d"
                text-color="text-gold"
                label="Approved"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                :amount="$totalNotApproved"
                border-color="#863d63"
                text-color="text-purple"
                label="Waiting Approval"
            />
        </div>
    </div>

    <x-common.content-wrapper>
        <x-content.header title="Invoices">
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
            <x-common.fail-message :message="session('failedMessage')" />
            <x-datatables.filter filters="'branch', 'status','paymentType'">
                <div class="columns is-marginless is-vcentered">
                    @if (authUser()->getAllowedWarehouses('transactions')->count() > 1)
                        <div class="column is-3 p-lr-0 pt-0">
                            <x-forms.field class="has-text-centered">
                                <x-forms.control>
                                    <x-forms.select
                                        id=""
                                        name=""
                                        class="is-size-7-mobile is-fullwidth"
                                        x-model="filters.branch"
                                        x-on:change="add('branch')"
                                    >
                                        <option
                                            disabled
                                            selected
                                            value=""
                                        >
                                            Branches
                                        </option>
                                        <option value="all"> All </option>
                                        @foreach (authUser()->getAllowedWarehouses('transactions') as $warehouse)
                                            <option value="{{ $warehouse->id }}"> {{ $warehouse->name }} </option>
                                        @endforeach
                                    </x-forms.select>
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                    @endif
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
                                    @foreach (['Waiting Approval', 'Approved', 'Subtracted', 'Voided'] as $status)
                                        <option value="{{ str()->lower($status) }}"> {{ $status }} </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-3 p-lr-0 pt-0">
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id=""
                                    name=""
                                    class="is-size-7-mobile is-fullwidth"
                                    x-model="filters.paymentType"
                                    x-on:change="add('paymentType')"
                                >
                                    <option
                                        disabled
                                        selected
                                        value=""
                                    >
                                        Payment Method
                                    </option>
                                    <option value="all"> All </option>
                                    @foreach (['Cash Payment', 'Credit Payment', 'Bank Deposit', 'Bank Transfer', 'Deposits', 'Cheque'] as $paymentType)
                                        <option value="{{ str()->lower($paymentType) }}"> {{ $paymentType }} </option>
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
