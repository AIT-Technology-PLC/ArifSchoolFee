@extends('layouts.app')

@section('title', 'Credits')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-3 p-lr-0">
            <x-common.total-model
                model="adjustments"
                :amount="$totalCredits"
                icon="fas fa-money-check"
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
                label="Partially Settlements"
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
    </div>

    <x-common.content-wrapper>
        <x-content.header title="Credits" />
        <x-content.footer>
            <x-common.success-message :message="session('deleted')" />
            {{ $datatable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
