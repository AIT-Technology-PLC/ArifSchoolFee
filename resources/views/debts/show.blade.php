@extends('layouts.app')

@section('title', 'Debt Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="General Information">
        </x-content.header>
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-money-check-dollar"
                        :data="$debt->code"
                        label="Debt No"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-file-invoice"
                        :data="$debt->purchase->code ?? 'N/A'"
                        label="Purchase No"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-user"
                        :data="$debt->supplier->company_name"
                        label="Supplier"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-spinner"
                        data="{{ number_format($debt->settlement_percentage, 2) }}%"
                        label="Settlement Percentage"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$debt->issued_on->toFormattedDateString()"
                        label="Issued On"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$debt->due_date->toFormattedDateString()"
                        label="Due Date"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$debt->last_settled_at ? $debt->last_settled_at->toFormattedDateString() : 'N/A'"
                        label="Last Settlement Date"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-money-check"
                        data="{{ number_format($debt->debt_amount, 2) }}"
                        label="Debt Amount in {{ userCompany()->currency }}"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-money-check"
                        data="{{ number_format($debt->debt_amount_settled, 2) }}"
                        label="Settled Amount in {{ userCompany()->currency }}"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-money-check"
                        data="{{ number_format($debt->debt_amount_unsettled, 2) }}"
                        label="Unsettled Amount in {{ userCompany()->currency }}"
                    />
                </div>
                @foreach ($debt->customFieldValues as $field)
                    <div class="column is-6">
                        <x-common.show-data-section
                            :icon="$field->customField->icon"
                            :data="$field->value"
                            :label="$field->customField->label"
                        />
                    </div>
                @endforeach
                @if (!is_null($debt->description))
                    <div class="column is-12">
                        <x-common.show-data-section
                            type="long"
                            :data="$debt->description"
                            label="Details"
                        />
                    </div>
                @endif
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header
            title="Debt Settlements"
            is-mobile
        >
            <x-common.dropdown name="Actions">
                @if (!$debt->isSettled())
                    @can('Settle Debt')
                        <x-common.dropdown-item>
                            <x-common.button
                                tag="a"
                                href="{{ route('debts.debt-settlements.create', $debt->id) }}"
                                mode="button"
                                icon="fas fa-money-check-dollar"
                                label="Add Settlement"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @endif
                @can('Update Debt')
                    <x-common.dropdown-item>
                        <x-common.button
                            tag="a"
                            href="{{ route('debts.edit', $debt->id) }}"
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
            @if ($debt->isSettled())
                <x-common.success-message message="This debt is fully settled." />
            @elseif ($debt->settlement_percentage)
                <x-common.fail-message message="This debt is partially settled." />
            @else
                <x-common.fail-message message="No settlements was made to this debt." />
            @endif
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
