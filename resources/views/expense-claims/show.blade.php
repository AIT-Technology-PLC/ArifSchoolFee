@extends('layouts.app')

@section('title', 'Expense Claim Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="General Information" />
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fa-solid fa-file-invoice-dollar"
                        :data="$expenseClaim->code"
                        label="Claim No"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-user"
                        :data="$expenseClaim->employee->user->name"
                        label="Employee Name"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-money-bill"
                        :data="$expenseClaim->totalPrice"
                        label="Total Price"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$expenseClaim->issued_on->toFormattedDateString()"
                        label="Issued On"
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
                @if (!$expenseClaim->isApproved())
                    @can('Approve Expense Claim')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('expense-claims.approve', $expenseClaim->id)"
                                action="approve"
                                intention="approve this expense claim"
                                icon="fas fa-signature"
                                label="Approve"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @endif
                @if (!$expenseClaim->isRejected())
                    @can('Reject Expense Claim')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('expense-claims.reject', $expenseClaim->id)"
                                action="reject"
                                intention="reject this expense claim"
                                icon="fas fa-times-circle"
                                label="Reject"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @endif
                <x-common.dropdown-item>
                    <x-common.button
                        tag="a"
                        href="{{ route('expense-claims.edit', $expenseClaim->id) }}"
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
            @if (!$expenseClaim->isApproved() && !$expenseClaim->isRejected())
                <x-common.fail-message message="This expense claim has not been approved yet." />
            @endif
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
