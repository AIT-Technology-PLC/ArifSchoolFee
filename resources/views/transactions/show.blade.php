@extends('layouts.app')

@section('title', $transaction->pad->name . ' Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="General Information" />
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        :icon="$transaction->pad->icon"
                        :data="$transaction->code"
                        label="{{ $transaction->pad->abbreviation }} No"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$transaction->issued_on->toFormattedDateString()"
                        label="Issued On"
                    />
                </div>
                @foreach ($masterTransactionFields as $masterTransactionField)
                    <div class="column is-6">
                        <x-common.show-data-section
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
                    @if (!userCompany()->isDiscountBeforeVAT())
                        <div class="column is-6">
                            <x-common.show-data-section
                                icon="fas fa-dollar-sign"
                                :data="number_format($transaction->grand_total_price_after_discount, 2)"
                                label="Grand Total Price (After Discount) ({{ userCompany()->currency }})"
                            />
                        </div>
                    @endif
                @endif
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header title="Details">
            @if ($transaction->pad->isApprovable() && !$transaction->isApproved())
                @can('approve', $transaction)
                    <x-common.transaction-button
                        :route="route('transactions.approve', $transaction->id)"
                        action="approve"
                        intention="approve this transaction"
                        icon="fas fa-signature"
                        label="Approve Transaction"
                        class="has-text-weight-medium"
                    />
                @endcan
            @elseif($transaction->pad->isInventoryOperationSubtract() && !$transaction->isSubtracted())
                @can('subtract', $transaction)
                    <x-common.transaction-button
                        :route="route('transactions.subtract', $transaction->id)"
                        action="subtract"
                        intention="subtract this transaction"
                        icon="fas fa-signature"
                        label="Subtract Transaction"
                        class="has-text-weight-medium"
                    />
                @endcan
            @elseif($transaction->pad->isInventoryOperationAdd() && !$transaction->isAdded())
                @can('add', $transaction)
                    <x-common.transaction-button
                        :route="route('transactions.add', $transaction->id)"
                        action="add"
                        intention="add this transaction"
                        icon="fas fa-signature"
                        label="Add Transaction"
                        class="has-text-weight-medium"
                    />
                @endcan
            @elseif($transaction->pad->isClosable() && !$transaction->isClosed())
                @can('close', $transaction)
                    <x-common.transaction-button
                        :route="route('transactions.close', $transaction->id)"
                        action="close"
                        intention="close this transaction"
                        icon="fas fa-signature"
                        label="Close Transaction"
                        class="has-text-weight-medium"
                    />
                @endcan
            @elseif($transaction->pad->isCancellable() && !$transaction->isCancelled())
                @can('cancel', $transaction)
                    <x-common.transaction-button
                        :route="route('transactions.cancel', $transaction->id)"
                        action="cancel"
                        intention="cancel this transaction"
                        icon="fas fa-signature"
                        label="Cancel Transaction"
                        class="has-text-weight-medium"
                    />
                @endcan
            @endif
            @if (!$transaction->isApproved() && !$transaction->isCancelled() && !$transaction->isClosed() && !$transaction->isAdded() && !$transaction->isSubtracted())
                @can('update', $transaction)
                    <x-common.button
                        tag="a"
                        href="{{ route('transactions.edit', $transaction->id) }}"
                        mode="button"
                        icon="fas fa-pen"
                        label="Edit"
                        class="is-small bg-green has-text-white"
                    />
                @endcan
            @endif
        </x-content.header>
        <x-content.footer>
            <x-common.fail-message :message="session('failedMessage')" />
            <x-common.success-message :message="session('successMessage') ?? session('deleted')" />
            @if ($transaction->pad->isCancellable() && $transaction->isCancelled())
                <x-common.fail-message message="This transaction is cancelled." />
            @elseif ($transaction->pad->isClosable() && $transaction->isClosed())
                <x-common.success-message message="This transaction is closed and archived." />
            @elseif ($transaction->pad->isInventoryOperationAdd() && $transaction->isAdded())
                <x-common.success-message message="Products have been added to the inventory." />
            @elseif ($transaction->pad->isInventoryOperationSubtract() && $transaction->isSubtracted())
                <x-common.success-message message="Products have been subtracted from the inventory." />
            @elseif ($transaction->pad->isApprovable() && $transaction->isApproved())
                <x-common.success-message message="This transaction is approved." />
            @elseif ($transaction->pad->isApprovable() && !$transaction->isApproved())
                <x-common.fail-message message="This transaction is not approved yet." />
            @endif

            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
