@extends('layouts.app')

@section('title', 'Prices')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-3 p-lr-0">
            <x-common.total-model
                model="products"
                :amount="$totalProducts"
                icon="fas fa-th"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                :amount="$totalFixedPrices"
                border-color="#3d8660"
                text-color="text-green"
                label="Fixed Price Products"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                :amount="$totalRangePrices"
                border-color="#86843d"
                text-color="text-gold"
                label="Range Price Products"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                :amount="$totalNoPrices"
                border-color="#863d63"
                text-color="text-purple"
                label="No Price Products"
            />
        </div>
    </div>

    <x-common.content-wrapper>
        <x-content.header title="Prices">
            @can('Create Price')
                <x-common.button
                    tag="a"
                    href="{{ route('prices.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Prices"
                    class="btn-green is-outlined is-small"
                />
            @endcan
            @can('Update Price')
                <x-common.button
                    tag="a"
                    href="{{ route('prices.edit') }}"
                    mode="button"
                    icon="fas fa-pen"
                    label="Update Prices"
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
