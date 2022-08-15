@extends('layouts.app')

@section('title', 'Receivables & Aging Report')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-6 p-lr-0">
            <x-common.total-model
                model="Total Receivables ({{ userCompany()->currency }})"
                :amount="number_format($totalReceivables, 2)"
                icon="fas fa-hand-holding-dollar"
            />
        </div>
        <div class="column is-6 p-lr-0">
            <x-common.index-insight
                :amount="$totalCustomers"
                border-color="#3d8660"
                text-color="text-green"
                label="Customers"
            />
        </div>
    </div>

    <x-common.content-wrapper>
        <x-content.header title="Receivables & Aging Report (as of {{ today()->toFormattedDateString() }})">
        </x-content.header>
        <x-content.footer>
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
