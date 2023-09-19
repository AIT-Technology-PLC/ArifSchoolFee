@extends('layouts.app')

@section('title', 'Inventory In Transit Report')

@section('content')
    <x-common.fail-message :message="session('failedMessage')" />

    <x-common.report-filter action="{{ route('reports.inventory_in_transit') }}">
        <div class="quickview-body">
            <div class="quickview-block">
                <div class="columns is-marginless is-vcentered is-multiline is-mobile">
                    <div class="column is-12">
                        <x-forms.label>
                            Transaction
                        </x-forms.label>
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id="transaction_type"
                                    name="transaction_type"
                                    class="is-size-7-mobile is-fullwidth"
                                >
                                    <option disabled> Transaction </option>
                                    <option
                                        value="transfers"
                                        @selected(request('transaction_type') == 'transfers')
                                    >
                                        Transfers
                                    </option>
                                    <option
                                        value="purchases"
                                        @selected(request('transaction_type') == 'purchases')
                                    >
                                        Purchases
                                    </option>
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </div>
        </div>
    </x-common.report-filter>

    <div class="columns is-marginless is-multiline mt-3">
        <div class="column is-12 p-lr-0">
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-green has-text-weight-medium is-size-6">
                        <span class="icon mr-1">
                            <i class="fas fa-chart-bar"></i>
                        </span>
                        <span>
                            Inventory In Transit Report For {{ str(request('transaction_type') ?? 'transfers')->title() }}
                        </span>
                    </h1>
                </x-slot:header>
            </x-content.header>
            <x-content.footer>
                {{ $dataTable->table() }}
            </x-content.footer>
        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
