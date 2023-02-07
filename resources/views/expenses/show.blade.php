@extends('layouts.app')

@section('title', 'Expense Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="General Information" />
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fa-solid fa-money-bill-trend-up"
                        :data="$expense->code"
                        label="Expense No"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$expense->issued_on->toFormattedDateString()"
                        label="Issued On"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-file-invoice-dollar"
                        :data="$expense->taxModel->type"
                        label="Tax Type"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-hashtag"
                        :data="$expense->reference_number"
                        label="Reference No"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-user"
                        :data="$expense->supplier?->company_name"
                        label="Supplier"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-address-card"
                        :data="$expense->contact->name ?? 'N/A'"
                        label="Contact"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-dollar-sign"
                        :data="number_format($expense->subtotalPrice, 2)"
                        label="Subtotal Price ({{ userCompany()->currency }})"
                    />
                </div>
                @if ($expense->taxModel->type != 'NONE')
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fa fa-dollar-sign"
                            :data="$expense->tax"
                            :label="$expense->taxModel->type ?: 'None'"
                        />
                    </div>
                @endif
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-dollar-sign"
                        :data="number_format($expense->grandTotalPrice, 2)"
                        label="Grand Total Price ({{ userCompany()->currency }})"
                    />
                </div>
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header
            title="Details"
            is-mobile
        >
            <x-common.dropdown name="Actions">
                @if (!$expense->isApproved())
                    @can('Approve Expense')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('expenses.approve', $expense->id)"
                                action="approve"
                                intention="approve this expense"
                                icon="fas fa-signature"
                                label="Approve"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @endif
                <x-common.dropdown-item>
                    <x-common.button
                        tag="a"
                        href="{{ route('expenses.edit', $expense->id) }}"
                        mode="button"
                        icon="fas fa-pen"
                        label="Edit"
                        class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                    />
                </x-common.dropdown-item>
            </x-common.dropdown>
        </x-content.header>
        <x-content.footer>
            <x-common.fail-message :message="session('failedMessage')" />
            <x-common.success-message :message="session('successMessage') ?? session('deleted')" />
            @if (!$expense->isApproved())
                <x-common.fail-message message="This Expense has not been approved yet." />
            @endif
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
