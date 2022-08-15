@extends('layouts.app')

@section('title', 'Receivables')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-6 p-lr-0">
            <x-common.total-model
                model="Receivables"
                :amount="$totalReceivables"
                icon="fas fa-hand-holding-dollar"
            />
        </div>
        <div class="column is-6 p-lr-0">
            <x-common.index-insight
                :amount="$totalCustomersWithUnSettlement"
                border-color="#3d8660"
                text-color="text-green"
                label="Customers"
            />
        </div>
    </div>

    <x-common.content-wrapper>
        <x-content.header title="Receivables">
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('deleted')" />
            <x-common.fail-message :message="session('failedMessage')" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
