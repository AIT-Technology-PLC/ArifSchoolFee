@extends('layouts.app')

@section('title', str()->singular($transaction->pad->name) . ' Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header
            title="General Information"
            is-mobile
        >
            @if (!$hasDetails)
                @include('transactions.partials.actions-dropdown')
            @endif
        </x-content.header>
        <x-content.footer>
            @if (!$hasDetails)
                <x-common.success-message :message="session('successMessage') ?? session('deleted')" />
                <x-common.fail-message :message="session('failedMessage')" />
            @endif

            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        :icon="$transaction->pad->icon"
                        :data="$transaction->code"
                        label="{{ $transaction->pad->abbreviation }} No"
                    />
                </div>
                @if ($transaction->status)
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-info"
                            :data="$transaction->status"
                            label="Status"
                        />
                    </div>
                @endif
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$transaction->issued_on->toFormattedDateString()"
                        label="Issued On"
                    />
                </div>
                @foreach ($masterTransactionFields as $masterTransactionField)
                    @continue($masterTransactionField->padField->isTagTextarea())
                    <div class="column is-6">
                        <x-common.show-data-section
                            type="short"
                            :icon="$masterTransactionField->padField->icon"
                            :data="$masterTransactionField->padField->hasRelation() ? $masterTransactionField->relationValue : $masterTransactionField->value"
                            :label="$masterTransactionField->padField->label"
                        />
                    </div>
                @endforeach
                @if ($transaction->pad->hasPrices())
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-dollar-sign"
                            :data="number_format($transaction->subtotal_price, 2)"
                            label="Subtotal Price ({{ userCompany()->currency }})"
                        />
                    </div>
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-dollar-sign"
                            :data="number_format($transaction->grand_total_price, 2)"
                            label="Grand Total Price ({{ userCompany()->currency }})"
                        />
                    </div>
                    @if (!userCompany()->isDiscountBeforeTax())
                        <div class="column is-6">
                            <x-common.show-data-section
                                icon="fas fa-dollar-sign"
                                :data="number_format($transaction->grand_total_price_after_discount, 2)"
                                label="Grand Total Price (After Discount) ({{ userCompany()->currency }})"
                            />
                        </div>
                    @endif
                @endif
                @foreach ($masterTransactionFields as $masterTransactionField)
                    @continue(!$masterTransactionField->padField->isTagTextarea())
                    <div class="column is-12">
                        <x-common.show-data-section
                            type="long"
                            :icon="$masterTransactionField->padField->icon"
                            :data="$masterTransactionField->value"
                            :label="$masterTransactionField->padField->label"
                        />
                    </div>
                @endforeach
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    @if ($hasDetails)
        <x-common.content-wrapper class="mt-5">
            <x-content.header
                title="Details"
                is-mobile
            >
                @include('transactions.partials.actions-dropdown')
            </x-content.header>
            <x-content.footer>
                <x-common.fail-message :message="session('failedMessage')" />
                <x-common.success-message :message="session('successMessage') ?? session('deleted')" />
                @if ($transaction->pad->isInventoryOperationAdd() && $transaction->isAdded())
                    <x-common.success-message message="Products have been added to the inventory." />
                @elseif ($transaction->pad->isInventoryOperationAdd() && $transaction->isPartiallyAdded())
                    <x-common.success-message message="Products have been partially added to the inventory." />
                @elseif ($transaction->pad->isInventoryOperationSubtract() && $transaction->isSubtracted())
                    <x-common.success-message message="Products have been subtracted from the inventory." />
                @elseif ($transaction->pad->isInventoryOperationSubtract() && $transaction->isPartiallySubtracted())
                    <x-common.success-message message="Products have been partially subtracted from the inventory." />
                @elseif ($transaction->pad->isApprovable() && $transaction->isApproved())
                    <x-common.success-message message="This transaction is approved." />
                @elseif ($transaction->pad->isApprovable() && !$transaction->isApproved())
                    <x-common.fail-message message="This transaction is not approved yet." />
                @endif

                {{ $dataTable->table() }}
            </x-content.footer>
        </x-common.content-wrapper>
    @endif

    @if ($transaction->pad->padStatuses->isNotEmpty())
        @can('update', $transaction)
            @include('transactions.partials.update-status')
        @endcan
    @endif
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
