@extends('layouts.app')

@section('title', 'Payables & Aging Report')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-6 p-lr-0">
            <x-common.total-model
                model="Total Payables ({{ userCompany()->currency }})"
                :amount="number_format($totalPayables, 2)"
                icon="fas fa-hand-holding-dollar"
            />
        </div>
        <div class="column is-6 p-lr-0">
            <x-common.index-insight
                :amount="$totalSuppliers"
                border-color="#3d8660"
                text-color="text-green"
                label="Suppliers"
            />
        </div>
    </div>

    <x-common.content-wrapper>
        <x-content.header title="Payables & Aging Report (as of {{ today()->toFormattedDateString() }})">
        </x-content.header>
        <x-content.footer>
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
