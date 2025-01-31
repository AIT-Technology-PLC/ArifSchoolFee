@extends('layouts.app')

@section('title', 'Currency History')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="{{ str($currency->code)->append(' - '.$currency->name )}}" />
        <x-content.footer>
            <x-common.success-message :message="session('successMessage')" />
            <div class="columns is-marginless is-multiline is-mobile">
                <div class="column is-6-mobile is-6-tablet is-4-desktop">
                    <x-common.show-data-section
                        icon="fas fa-dollar"
                        :data="$currency->name ?? 'N/A'"
                        label="Name"
                    />
                </div>
                <div class="column is-6-mobile is-6-tablet is-4-desktop">
                    <x-common.show-data-section
                        icon="fas fa-hashtag"
                        :data="$currency->code ?? 'N/A'"
                        label="Code"
                    />
                </div>
                <div class="column is-6-mobile is-6-tablet is-4-desktop">
                    <x-common.show-data-section
                        icon="fas fa-heading"
                        :data="$currency->symbol ?? 'N/A'"
                        label="Symbol"
                    />
                </div>
                <div class="column is-6-mobile is-6-tablet is-4-desktop">
                    <x-common.show-data-section
                        icon="fas fa-exchange-alt"
                        :data="$currency->exchange_rate ?? '0.00'"
                        label="Exchange Rate"
                    />
                </div>
                <div class="column is-6-mobile is-6-tablet is-4-desktop">
                    <x-common.show-data-section
                        icon="fas fa-sort"
                        :data="$currency->enabled ? 'Yes' : 'No'"
                        label="Enable Exchange Rate"
                    />
                </div>
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header title="Exchange Rate History">
        </x-content.header>
        <x-content.footer>
            <div> {{ $dataTable->table() }} </div>
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
