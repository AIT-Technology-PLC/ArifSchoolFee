@if (!request()->ajax())
    @props(['buttons', 'model', 'id'])
@endif

@if ($buttons == 'all' || in_array('details', $buttons))
    <x-common.details-button
        route="{{ $model }}.show"
        :id="$id"
    />
@endif

@if ($buttons == 'all' || in_array('edit', $buttons))
    <x-common.edit-button
        route="{{ $model }}.edit"
        :id="$id"
    />
@endif

@if ((($transaction->pad->isApprovable() && $transaction->isApproved()) || !$transaction->pad->isApprovable()) &&
    $transaction->pad->isInventoryOperationSubtract() &&
    !App\Models\TransactionField::isSubtracted($transaction, $line) &&
    !$transaction->isSubtracted() &&
    ($buttons == 'all' || in_array('subtract', $buttons)))
    <x-common.transaction-button
        :route="route($model . '.subtract', $id)"
        action="subtract"
        intention="subtract this product from the inventory"
        icon="fas fa-minus-circle"
        data-title="Subtract from inventory"
        class="text-green has-text-weight-medium is-not-underlined is-small px-2 py-0 is-transparent-color"
    />
@endif

@if ((($transaction->pad->isApprovable() && $transaction->isApproved()) || !$transaction->pad->isApprovable()) &&
    $transaction->pad->isInventoryOperationAdd() &&
    !App\Models\TransactionField::isAdded($transaction, $line) &&
    !$transaction->isAdded() &&
    ($buttons == 'all' || in_array('add', $buttons)))
    <x-common.transaction-button
        :route="route($model . '.add', $id)"
        action="add"
        intention="add this product to the inventory"
        icon="fas fa-plus"
        data-title="Add to inventory"
        class="text-green has-text-weight-medium is-not-underlined is-small px-2 py-0 is-transparent-color"
    />
@endif

@if ($buttons == 'all' || in_array('delete', $buttons))
    <x-common.delete-button
        route="{{ $model }}.destroy"
        :id="$id"
    />
@endif
