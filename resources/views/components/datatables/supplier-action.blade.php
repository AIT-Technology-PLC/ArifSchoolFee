@if (isFeatureEnabled('Debt Management') && $supplier->debts()->sum('debt_amount') != $supplier->debts()->sum('debt_amount_settled'))
    @can('Settle Debt')
        <x-common.button
            tag="a"
            href="{{ route('suppliers.settle', $supplier->id) }}"
            mode="button"
            data-title="Settle Debt"
            icon="fas fa-money-check-dollar"
            class="text-green has-text-weight-medium is-not-underlined is-small px-2 py-0 is-transparent-color"
        />
    @endcan
@endif

<x-common.action-buttons
    :buttons="['delete', 'edit']"
    model="suppliers"
    :id="$supplier->id"
/>
