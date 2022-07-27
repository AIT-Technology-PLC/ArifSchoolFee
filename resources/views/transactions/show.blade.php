@extends('layouts.app')

@section('title', str()->singular($transaction->pad->name) . ' Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header
            title="General Information"
            is-mobile
        >
            @if (!$hasDetails)
                <x-common.dropdown name="Actions">
                    @if ($transaction->pad->isApprovable() && !$transaction->isApproved())
                        @can('approve', $transaction)
                            <x-common.dropdown-item>
                                <x-common.transaction-button
                                    :route="route('transactions.approve', $transaction->id)"
                                    action="approve"
                                    intention="approve this transaction"
                                    icon="fas fa-signature"
                                    label="Approve"
                                    class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                                />
                            </x-common.dropdown-item>
                        @endcan
                    @elseif($transaction->pad->isInventoryOperationSubtract() && !$transaction->isSubtracted())
                        @can('subtract', $transaction)
                            <x-common.dropdown-item>
                                <x-common.transaction-button
                                    :route="route('transactions.subtract', $transaction->id)"
                                    action="subtract"
                                    intention="subtract this transaction"
                                    icon="fas fa-minus-circle"
                                    label="Subtract"
                                    class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                                />
                            </x-common.dropdown-item>
                        @endcan
                    @elseif($transaction->pad->isInventoryOperationAdd() && !$transaction->isAdded())
                        @can('add', $transaction)
                            <x-common.dropdown-item>
                                <x-common.transaction-button
                                    :route="route('transactions.add', $transaction->id)"
                                    action="add"
                                    intention="add this transaction"
                                    icon="fas fa-plus-circle"
                                    label="Add"
                                    class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                                />
                            </x-common.dropdown-item>
                        @endcan
                    @elseif($transaction->pad->isClosable() && !$transaction->isClosed())
                        @can('close', $transaction)
                            <x-common.dropdown-item>
                                <x-common.transaction-button
                                    :route="route('transactions.close', $transaction->id)"
                                    action="close"
                                    intention="close this transaction"
                                    icon="fas fa-signature"
                                    label="Close"
                                    class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                                />
                            </x-common.dropdown-item>
                        @endcan
                    @elseif($transaction->pad->isCancellable() && !$transaction->isCancelled())
                        @can('cancel', $transaction)
                            <x-common.dropdown-item>
                                <x-common.transaction-button
                                    :route="route('transactions.cancel', $transaction->id)"
                                    action="cancel"
                                    intention="cancel this transaction"
                                    icon="fas fa-signature"
                                    label="Cancel"
                                    class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                                />
                            </x-common.dropdown-item>
                        @endcan
                    @endif
                    @if (!$transaction->isApproved() && !$transaction->isCancelled() && !$transaction->isClosed() && !$transaction->isAdded() && !$transaction->isSubtracted())
                        @can('update', $transaction)
                            <x-common.dropdown-item>
                                <x-common.button
                                    tag="a"
                                    href="{{ route('transactions.edit', $transaction->id) }}"
                                    mode="button"
                                    icon="fas fa-pen"
                                    label="Edit"
                                    class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                                />
                            </x-common.dropdown-item>
                        @endcan
                    @endif
                </x-common.dropdown>
            @endif
        </x-content.header>
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        :icon="$transaction->pad->icon"
                        :data="$transaction->code"
                        label="{{ str()->singular($transaction->pad->abbreviation) }} No"
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
                    <div class="column {{ $masterTransactionField->padField->isTagTextarea() ? 'is-12' : 'is-6' }}">
                        <x-common.show-data-section
                            :type="$masterTransactionField->padField->isTagTextarea() ? 'long' : 'short'"
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

    @if ($hasDetails)
        <x-common.content-wrapper class="mt-5">
            <x-content.header
                title="Details"
                is-mobile
            >
                <x-common.dropdown name="Actions">
                    @if ($transaction->pad->isApprovable() && !$transaction->isApproved())
                        @can('approve', $transaction)
                            <x-common.dropdown-item>
                                <x-common.transaction-button
                                    :route="route('transactions.approve', $transaction->id)"
                                    action="approve"
                                    intention="approve this transaction"
                                    icon="fas fa-signature"
                                    label="Approve"
                                    class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                                />
                            </x-common.dropdown-item>
                        @endcan
                    @elseif($transaction->pad->isInventoryOperationSubtract() && !$transaction->isSubtracted())
                        @can('subtract', $transaction)
                            <x-common.dropdown-item>
                                <x-common.transaction-button
                                    :route="route('transactions.subtract', $transaction->id)"
                                    action="subtract"
                                    intention="subtract this transaction"
                                    icon="fas fa-minus-circle"
                                    label="Subtract"
                                    class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                                />
                            </x-common.dropdown-item>
                        @endcan
                    @elseif($transaction->pad->isInventoryOperationAdd() && !$transaction->isAdded())
                        @can('add', $transaction)
                            <x-common.dropdown-item>
                                <x-common.transaction-button
                                    :route="route('transactions.add', $transaction->id)"
                                    action="add"
                                    intention="add this transaction"
                                    icon="fas fa-plus-circle"
                                    label="Add"
                                    class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                                />
                            </x-common.dropdown-item>
                        @endcan
                    @elseif($transaction->pad->isClosable() && !$transaction->isClosed())
                        @can('close', $transaction)
                            <x-common.dropdown-item>
                                <x-common.transaction-button
                                    :route="route('transactions.close', $transaction->id)"
                                    action="close"
                                    intention="close this transaction"
                                    icon="fas fa-ban"
                                    label="Close"
                                    class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                                />
                            </x-common.dropdown-item>
                        @endcan
                    @elseif($transaction->pad->isCancellable() && !$transaction->isCancelled())
                        @can('cancel', $transaction)
                            <x-common.dropdown-item>
                                <x-common.transaction-button
                                    :route="route('transactions.cancel', $transaction->id)"
                                    action="cancel"
                                    intention="cancel this transaction"
                                    icon="fas fa-times-circle"
                                    label="Cancel"
                                    class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                                />
                            </x-common.dropdown-item>
                        @endcan
                    @endif
                    @if (!$transaction->isApproved() && !$transaction->isCancelled() && !$transaction->isClosed() && !$transaction->isAdded() && !$transaction->isSubtracted())
                        @can('update', $transaction)
                            <x-common.dropdown-item>
                                <x-common.button
                                    tag="a"
                                    href="{{ route('transactions.edit', $transaction->id) }}"
                                    mode="button"
                                    icon="fas fa-pen"
                                    label="Edit"
                                    class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                                />
                            </x-common.dropdown-item>
                        @endcan
                    @endif
                    @if ($transaction->pad->isPrintable() && (!$transaction->pad->isApprovable() || ($transaction->pad->isApprovable() && $transaction->isApproved())))
                        @can('view', $transaction)
                            <x-common.dropdown-item>
                                <x-common.button
                                    tag="a"
                                    href="{{ route('transactions.print', $transaction->id) }}"
                                    target="_blank"
                                    mode="button"
                                    icon="fas fa-print"
                                    label="Print"
                                    class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                                />
                            </x-common.dropdown-item>
                        @endcan
                    @endif
                    @foreach ($transaction->pad->convert_to as $feature)
                        @can('convert', $transaction)
                            <x-common.dropdown-item>
                                <x-common.button
                                    tag="a"
                                    href="{{ route('transactions.convert_to', [$transaction->id, 'target' => $feature]) }}"
                                    mode="button"
                                    icon="fas fa-file-invoice"
                                    label="Issue {{ str($feature)->singular()->title() }}"
                                    class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                                />
                            </x-common.dropdown-item>
                        @endcan
                    @endforeach
                </x-common.dropdown>
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
    @endif
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
