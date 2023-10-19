<x-common.dropdown name="Actions">
    @if ($transaction->pad->isApprovable() && !$transaction->isApproved() && $transaction->pad->isInventoryOperationSubtract() && !$transaction->isSubtracted() && authUser()->can(['approve', 'subtract'], $transaction))
        <x-common.dropdown-item>
            <x-common.transaction-button
                :route="route('transactions.approve_and_subtract', $transaction->id)"
                action="approve & subtract"
                intention="approve & subtract this transaction"
                icon="fas fa-minus-circle"
                label="Approve & Subtract"
                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
            />
        </x-common.dropdown-item>
    @elseif ($transaction->pad->isApprovable() && !$transaction->isApproved() && $transaction->pad->isInventoryOperationAdd() && !$transaction->isAdded() && authUser()->can(['approve', 'add'], $transaction))
        <x-common.dropdown-item>
            <x-common.transaction-button
                :route="route('transactions.approve_and_add', $transaction->id)"
                action="approve & add"
                intention="approve & add this transaction"
                icon="fas fa-plus-circle"
                label="Approve & Add"
                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
            />
        </x-common.dropdown-item>
    @elseif ($transaction->pad->isApprovable() && !$transaction->isApproved())
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
    @endif
    @if ((!$transaction->isApproved() && !$transaction->isAdded() && !$transaction->isSubtracted()) || $hasDescriptionBox)
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
    @if ($transaction->pad->isInventoryOperationNone() || $transaction->isAdded() || $transaction->isSubtracted())
        @foreach ($transaction->pad->convert_to as $feature)
            @can('convert', $transaction)
                @if (!is_null($transaction->pad->featureNames($feature)))
                    <x-common.dropdown-item>
                        <x-common.button
                            tag="a"
                            href="{{ route('transactions.convert_to', [$transaction->id, 'target' => $feature]) }}"
                            mode="button"
                            icon="fas fa-file-invoice"
                            label="Issue {{ $transaction->pad->featureNames($feature) }}"
                            class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                        />
                    </x-common.dropdown-item>
                @endif
            @endcan
        @endforeach
    @endif
    @if ($transaction->pad->padStatuses->isNotEmpty())
        @can('updateStatus', $transaction)
            <x-common.dropdown-item>
                <x-common.button
                    tag="button"
                    mode="button"
                    @click="$dispatch('open-update-status-modal')"
                    icon="fas fa-info"
                    label="Update Status"
                    class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                />
            </x-common.dropdown-item>
        @endcan
    @endif
</x-common.dropdown>
