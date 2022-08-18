@extends('layouts.app')

@section('title', 'Debit Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="General Information">
        </x-content.header>
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-money-check"
                        :data="$debit->code"
                        label="Debit No"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-file-invoice"
                        :data="$debit->purchase->code ?? 'N/A'"
                        label="Purchase No"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-user"
                        :data="$debit->supplier->company_name"
                        label="Supplier"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-spinner"
                        data="{{ number_format($debit->settlement_percentage, 2) }}%"
                        label="Settlement Percentage"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$debit->issued_on->toFormattedDateString()"
                        label="Issued On"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$debit->due_date->toFormattedDateString()"
                        label="Due Date"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$debit->last_settled_at ? $debit->last_settled_at->toFormattedDateString() : 'N/A'"
                        label="Last Settlement Date"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-money-check"
                        data="{{ number_format($debit->debit_amount, 2) }}"
                        label="Debit Amount in {{ userCompany()->currency }}"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-money-check"
                        data="{{ number_format($debit->debit_amount_settled, 2) }}"
                        label="Settled Amount in {{ userCompany()->currency }}"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-money-check"
                        data="{{ number_format($debit->debit_amount_unsettled, 2) }}"
                        label="Unsettled Amount in {{ userCompany()->currency }}"
                    />
                </div>
                @if (!is_null($debit->description))
                    <div class="column is-12">
                        <x-common.show-data-section
                            type="long"
                            :data="$debit->description"
                            label="Details"
                        />
                    </div>
                @endif
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header
            title="Debit Settlements"
            is-mobile
        >
            <x-common.dropdown name="Actions">
                @if (!$debit->isSettled())
                    @can('Settle Debit')
                        <x-common.dropdown-item>
                            <x-common.button
                                tag="a"
                                href="{{ route('debits.debit-settlements.create', $debit->id) }}"
                                mode="button"
                                icon="fas fa-money-check"
                                label="Add Settlement"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @endif
                @can('Update Debit')
                    <x-common.dropdown-item>
                        <x-common.button
                            tag="a"
                            href="{{ route('debits.edit', $debit->id) }}"
                            mode="button"
                            icon="fas fa-pen"
                            label="Edit"
                            class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                        />
                    </x-common.dropdown-item>
                @endcan
            </x-common.dropdown>
        </x-content.header>
        <x-content.footer>
            <x-common.fail-message :message="session('failedMessage')" />
            <x-common.success-message :message="session('deleted')" />
            @if ($debit->isSettled())
                <x-common.success-message message="This debit is fully settled." />
            @elseif ($debit->settlement_percentage)
                <x-common.fail-message message="This debit is partially settled." />
            @else
                <x-common.fail-message message="No settlements was made to this debit." />
            @endif
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
