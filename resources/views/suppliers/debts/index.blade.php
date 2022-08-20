@extends('layouts.app')

@section('title', 'Debts')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Supplier Profile" />
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-user"
                        :data="$supplier->company_name"
                        label="Supplier Name"
                    />
                </div>
                @if ($supplier->tin)
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-hashtag"
                            :data="$supplier->tin"
                            label="TIN No"
                        />
                    </div>
                @endif
                @if ($supplier->address)
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-map-marker"
                            :data="$supplier->address"
                            label="Address"
                        />
                    </div>
                @endif
                @if ($supplier->contact_name)
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-user-tie"
                            :data="$supplier->contact_name"
                            label="Contact Person"
                        />
                    </div>
                @endif
                @if ($supplier->email)
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-at"
                            :data="$supplier->email"
                            label="Email"
                        />
                    </div>
                @endif
                @if ($supplier->phone)
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-phone"
                            :data="$supplier->phone"
                            label="Phone Number"
                        />
                    </div>
                @endif
                @if ($supplier->country)
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-globe"
                            :data="$supplier->country"
                            label="Country/City"
                        />
                    </div>
                @endif
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper>
        <div class="columns is-marginless is-multiline">
            <div class="column is-3 p-lr-0 pl-0">
                <x-common.total-model
                    model="debts"
                    :amount="$totalDebts"
                    icon="fas fa-money-check-dollar"
                />
            </div>
            <div class="column is-3 p-lr-0 pl-0">
                <x-common.index-insight
                    :amount="$totalSettled"
                    border-color="#3d8660"
                    text-color="text-green"
                    label="Settled"
                />
            </div>
            <div class="column is-3 p-lr-0 pl-0">
                <x-common.index-insight
                    :amount="$totalPartiallySettled"
                    border-color="#86843d"
                    text-color="text-gold"
                    label="Partial Settlements"
                />
            </div>
            <div class="column is-3 p-lr-0 px-0">
                <x-common.index-insight
                    :amount="$totalNotSettledAtAll"
                    border-color="#863d63"
                    text-color="text-purple"
                    label="No Settlements"
                />
            </div>
            <div class="column is-6 p-lr-0 pl-0">
                <x-common.index-insight
                    amount="{{ number_format($supplier->debt_amount_limit, 2) }}"
                    border-color="#3d6386"
                    text-color="text-blue"
                    label="Debt Limit (in {{ userCompany()->currency }})"
                />
            </div>
            <div class="column is-6 p-lr-0 px-0">
                <x-common.index-insight
                    amount="{{ number_format($currentDebtLimit, 2) }}"
                    border-color="#863d3e"
                    text-color="text-brown"
                    label="Current Debt Limit (in {{ userCompany()->currency }})"
                />
            </div>
            <div class="column is-4 p-lr-0 pl-0">
                <x-common.index-insight
                    amount="{{ number_format($totalDebtAmountProvided, 2) }}"
                    border-color="#3d8660"
                    text-color="text-green"
                    label="Debt Provided To Date (in {{ userCompany()->currency }})"
                />
            </div>
            <div class="column is-4 p-lr-0 pl-0">
                <x-common.index-insight
                    amount="{{ number_format($currentDebtBalance, 2) }}"
                    border-color="#86843d"
                    text-color="text-gold"
                    label="Unsettled Debt Balance (in {{ userCompany()->currency }})"
                />
            </div>
            <div class="column is-4 p-lr-0 px-0">
                <x-common.index-insight
                    amount="{{ number_format($averageDebtSettlementDays, 2) }}"
                    border-color="#863d63"
                    text-color="text-purple"
                    label="Average Debt Settlement Period (in Days)"
                />
            </div>
        </div>
    </x-common.content-wrapper>

    <x-common.content-wrapper>
        <x-content.header title="Supplier Debts" />
        <x-content.footer>
            <x-common.success-message :message="session('deleted') ?? session('successMessage')" />
            <x-common.fail-message :message="session('failedMessage')" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
