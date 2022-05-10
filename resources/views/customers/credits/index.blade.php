@extends('layouts.app')

@section('title', 'Credits')

@section('content')
<x-common.content-wrapper>
    <x-content.header title="Customer Profile" />
    <x-content.footer>
        <div class="columns is-marginless is-multiline">
            <div class="column is-6">
                <x-common.show-data-section icon="fas fa-user" :data="$customer->company_name" label="Customer Name" />
            </div>
            @if ($customer->tin)
            <div class="column is-6">
                <x-common.show-data-section icon="fas fa-hashtag" :data="$customer->tin" label="TIN No" />
            </div>
            @endif
            @if ($customer->address)
            <div class="column is-6">
                <x-common.show-data-section icon="fas fa-map-marker" :data="$customer->address" label="Address" />
            </div>
            @endif
            @if ($customer->contact_name)
            <div class="column is-6">
                <x-common.show-data-section icon="fas fa-user-tie" :data="$customer->contact_name"
                    label="Contact Person" />
            </div>
            @endif
            @if ($customer->email)
            <div class="column is-6">
                <x-common.show-data-section icon="fas fa-at" :data="$customer->email" label="Email" />
            </div>
            @endif
            @if ($customer->phone)
            <div class="column is-6">
                <x-common.show-data-section icon="fas fa-phone" :data="$customer->phone" label="Phone Number" />
            </div>
            @endif
            @if ($customer->country)
            <div class="column is-6">
                <x-common.show-data-section icon="fas fa-globe" :data="$customer->country" label="Country/City" />
            </div>
            @endif
        </div>
    </x-content.footer>
</x-common.content-wrapper>

<x-common.content-wrapper>
    <div class="columns is-marginless is-multiline">
        <div class="column is-3 p-lr-0 pl-0">
            <x-common.total-model model="credits" :amount="$totalCredits" icon="fas fa-money-check" />
        </div>
        <div class="column is-3 p-lr-0 pl-0">
            <x-common.index-insight :amount="$totalSettled" border-color="#3d8660" text-color="text-green"
                label="Settled" />
        </div>
        <div class="column is-3 p-lr-0 pl-0">
            <x-common.index-insight :amount="$totalPartiallySettled" border-color="#86843d" text-color="text-gold"
                label="Partial Settlements" />
        </div>
        <div class="column is-3 p-lr-0 px-0">
            <x-common.index-insight :amount="$totalNotSettledAtAll" border-color="#863d63" text-color="text-purple"
                label="No Settlements" />
        </div>
        <div class="column is-6 p-lr-0 pl-0">
            <x-common.index-insight amount="{{ number_format($customer->credit_amount_limit, 2) }}"
                border-color="#3d6386" text-color="text-blue" label="Credit Limit (in {{ userCompany()->currency }})" />
        </div>
        <div class="column is-6 p-lr-0 px-0">
            <x-common.index-insight amount="{{ number_format($currentCreditLimit, 2) }}" border-color="#863d3e"
                text-color="text-brown" label="Current Credit Limit (in {{ userCompany()->currency }})" />
        </div>
        <div class="column is-4 p-lr-0 pl-0">
            <x-common.index-insight amount="{{ number_format($totalCreditAmountProvided, 2) }}" border-color="#3d8660"
                text-color="text-green" label="Credit Provided To Date (in {{ userCompany()->currency }})" />
        </div>
        <div class="column is-4 p-lr-0 pl-0">
            <x-common.index-insight amount="{{ number_format($currentCreditBalance, 2) }}" border-color="#86843d"
                text-color="text-gold" label="Unsettled Credit Balance (in {{ userCompany()->currency }})" />
        </div>
        <div class="column is-4 p-lr-0 px-0">
            <x-common.index-insight amount="{{ number_format($averageCreditSettlementDays, 2) }}" border-color="#863d63"
                text-color="text-purple" label="Average Credit Settlement Period (in Days)" />
        </div>
    </div>
</x-common.content-wrapper>

<x-common.content-wrapper>
    <x-content.header title="Customer Credits" />
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