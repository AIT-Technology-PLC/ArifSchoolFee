@extends('layouts.app')

@section('title', 'Deposits')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Deposit Profile" />
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-user"
                        :data="$customer->company_name"
                        label="Customer Name"
                    />
                </div>
                @if ($customer->tin)
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-hashtag"
                            :data="$customer->tin"
                            label="TIN No"
                        />
                    </div>
                @endif
                @if ($customer->address)
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-map-marker"
                            :data="$customer->address"
                            label="Address"
                        />
                    </div>
                @endif
                @if ($customer->contact_name)
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-user-tie"
                            :data="$customer->contact_name"
                            label="Contact Person"
                        />
                    </div>
                @endif
                @if ($customer->email)
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-at"
                            :data="$customer->email"
                            label="Email"
                        />
                    </div>
                @endif
                @if ($customer->phone)
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-phone"
                            :data="$customer->phone"
                            label="Phone Number"
                        />
                    </div>
                @endif
                @if ($customer->country)
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-globe"
                            :data="$customer->country"
                            label="Country/City"
                        />
                    </div>
                @endif
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper>
        <div class="columns is-marginless is-multiline">
            <div class="column is-4 p-lr-0 pl-0">
                <x-common.index-insight
                    amount="{{ $totalNumberOfDeposits }}"
                    border-color="#3d8660"
                    text-color="text-green"
                    label="Deposits"
                />
            </div>
            <div class="column is-4 p-lr-0 pl-0">
                <x-common.index-insight
                    amount="{{ number_format($totalDeposits, 2) }}"
                    border-color="#86843d"
                    text-color="text-gold"
                    label="Total Deposit (in {{ userCompany()->currency }})"
                />
            </div>
            <div class="column is-4 p-lr-0 px-0">
                <x-common.index-insight
                    amount="{{ number_format($customer->balance, 2) }}"
                    border-color="#86843d"
                    text-color="text-gold"
                    label="Available Deposit Balance (in {{ userCompany()->currency }})"
                />
            </div>
        </div>
    </x-common.content-wrapper>

    <x-common.content-wrapper>
        <x-content.header title="Deposits" />
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
