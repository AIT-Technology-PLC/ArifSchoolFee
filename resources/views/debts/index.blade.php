@extends('layouts.app')

@section('title', 'Debts')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-3 p-lr-0">
            <x-common.total-model
                model="debts"
                :amount="$totalDebts"
                icon="fas fa-money-check-dollar"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                :amount="$totalSettled"
                border-color="#3d8660"
                text-color="text-green"
                label="Settled"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                :amount="$totalPartiallySettled"
                border-color="#86843d"
                text-color="text-gold"
                label="Partial Settlements"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                :amount="$totalNotSettledAtAll"
                border-color="#863d63"
                text-color="text-purple"
                label="No Settlements"
            />
        </div>
        <div class="column is-6 p-lr-0">
            <x-common.index-insight
                amount="{{ number_format($currentDebtBalance, 2) }}"
                border-color="#3d8660"
                text-color="text-green"
                label="Unsettled Debt Balance (in {{ userCompany()->currency }})"
            />
        </div>
        <div class="column is-6 p-lr-0">
            <x-common.index-insight
                amount="{{ number_format($averageDebtSettlementDays, 2) }}"
                border-color="#863d63"
                text-color="text-purple"
                label="Average Debt Settlement Period (in Days)"
            />
        </div>
    </div>

    <x-common.content-wrapper>
        <x-content.header title="Debts">
            @can('Import Debt')
                <x-common.button
                    tag="button"
                    mode="button"
                    @click="$dispatch('open-import-modal') "
                    icon="fas fa-upload"
                    label="Import Debt"
                    class="btn-green is-outlined is-small"
                />
            @endcan
            @can('Create Debt')
                <x-common.button
                    tag="a"
                    href="{{ route('debts.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Debt"
                    class="btn-green is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('deleted') ?? (session('successMessage') ?? session('imported'))" />
            <x-common.fail-message :message="session('failedMessage') ?? (count($errors->all()) ? $errors->all() : null)" />
            <x-datatables.filter filters="'branch', 'status'">
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
                                    @foreach (['No Settlements', 'Partial Settlements', 'Settled'] as $status)
                                        <option value="{{ str()->lower($status) }}"> {{ $status }} </option>
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
    @can('Import Debt')
        <x-common.import
            title="Import Debts"
            action="{{ route('debts.import') }}"
        />
    @endcan
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
