@extends('layouts.app')

@section('title', 'Credit Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="General Information">
        </x-content.header>
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-money-check"
                        :data="$credit->code"
                        label="Credit No"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-file-invoice"
                        :data="$credit->creditable->code ?? 'N/A'"
                        label="Transaction No"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-user"
                        :data="$credit->customer->company_name"
                        label="Customer"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-spinner"
                        data="{{ number_format($credit->settlement_percentage, 2) }}%"
                        label="Settlement Percentage"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$credit->issued_on->toFormattedDateString()"
                        label="Issued On"
                    />
                </div>
                @if (!is_null($credit->due_date))
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-calendar-day"
                            :data="$credit->due_date->toFormattedDateString()"
                            label="Due Date"
                        />
                    </div>
                @endif
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$credit->last_settled_at ? $credit->last_settled_at->toFormattedDateString() : 'N/A'"
                        label="Last Settlement Date"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-money-check"
                        data="{{ number_format($credit->credit_amount, 2) }}"
                        label="Credit Amount in {{ userCompany()->currency }}"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-money-check"
                        data="{{ number_format($credit->credit_amount_settled, 2) }}"
                        label="Settled Amount in {{ userCompany()->currency }}"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-money-check"
                        data="{{ number_format($credit->credit_amount_unsettled, 2) }}"
                        label="Unsettled Amount in {{ userCompany()->currency }}"
                    />
                </div>
                @foreach ($credit->customFieldValues as $field)
                    <div class="column is-6">
                        <x-common.show-data-section
                            :icon="$field->customField->icon"
                            :data="$field->value"
                            :label="$field->customField->label"
                        />
                    </div>
                @endforeach
                @if (!is_null($credit->description))
                    <div class="column is-12">
                        <x-common.show-data-section
                            type="long"
                            :data="$credit->description"
                            label="Details"
                        />
                    </div>
                @endif
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header
            title="Credit Settlements"
            is-mobile
        >
            <x-common.dropdown name="Actions">
                @if (!$credit->isSettled())
                    @can('Settle Credit')
                        <x-common.dropdown-item>
                            <x-common.button
                                tag="a"
                                href="{{ route('credits.credit-settlements.create', $credit->id) }}"
                                mode="button"
                                icon="fas fa-money-check"
                                label="Add Settlement"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @endif
                @can('Update Credit')
                    <x-common.dropdown-item>
                        <x-common.button
                            tag="a"
                            href="{{ route('credits.edit', $credit->id) }}"
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
            @if ($credit->isSettled())
                <x-common.success-message message="This credit is fully settled." />
            @elseif ($credit->settlement_percentage)
                <x-common.fail-message message="This credit is partially settled." />
            @else
                <x-common.fail-message message="No settlements was made to this credit." />
            @endif
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
