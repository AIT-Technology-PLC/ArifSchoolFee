@extends('layouts.app')

@section('title', 'Delivery Orders')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-3 p-lr-0">
            <x-common.total-model
                model="Del. Orders"
                :amount="$totalGdns"
                icon="fas fa-file-invoice"
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
                :amount="$totalNotSubtracted"
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
        <x-content.header title="Delivery Orders">
            @can('Import GDN')
                <x-common.button
                    tag="button"
                    mode="button"
                    @click="$dispatch('open-import-modal') "
                    icon="fas fa-upload"
                    label="Import Delivery Order"
                    class="btn-green is-outlined is-small"
                />
            @endcan
            @can('Create GDN')
                <x-common.button
                    tag="a"
                    href="{{ route('gdns.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Delivery Order"
                    class="btn-green is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('deleted') ?? session('imported')" />
            <x-common.fail-message :message="count($errors->all()) ? $errors->all() : null" />
            <x-datatables.filter filters="'branch', 'status','paymentType', 'deliveryStatus'">
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
                                    @foreach (['Waiting Approval', 'Approved', 'Subtracted', 'Voided', 'Closed'] as $status)
                                        <option value="{{ str()->lower($status) }}"> {{ $status }} </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    @if (userCompany()->isPartialDeliveriesEnabled())
                        <div class="column is-3 p-lr-0 pt-0">
                            <x-forms.field class="has-text-centered">
                                <x-forms.control>
                                    <x-forms.select
                                        id=""
                                        name=""
                                        class="is-size-7-mobile is-fullwidth"
                                        x-model="filters.deliveryStatus"
                                        x-on:change="add('deliveryStatus')"
                                    >
                                        <option
                                            disabled
                                            selected
                                            value=""
                                        >
                                            Delivery Statuses
                                        </option>
                                        <option value="all"> All </option>
                                        @foreach (['Not Delivered', 'Partially Delivered', 'Fully Delivered'] as $deliveryStatus)
                                            <option value="{{ str()->lower($deliveryStatus) }}"> {{ $deliveryStatus }} </option>
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
            <div>
                {{ $dataTable->table() }}
            </div>
        </x-content.footer>
    </x-common.content-wrapper>
    @can('Import GDN')
        <x-common.import
            title="Import Delivery Order"
            action="{{ route('gdns.import') }}"
        />
    @endcan
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
