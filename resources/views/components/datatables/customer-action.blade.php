@if (isFeatureEnabled('Credit Management') && $customer->credits()->sum('credit_amount') != $customer->credits()->sum('credit_amount_settled'))
    @can('Settle Credit')
        <x-common.button
            tag="a"
            href="{{ route('customers.settle', $customer->id) }}"
            mode="button"
            data-title="Settle Credit"
            icon="fas fa-money-check"
            class="text-green has-text-weight-medium is-not-underlined is-small px-2 py-0 is-transparent-color"
        />
    @endcan
@endif

<x-common.action-buttons
    :buttons="['delete', 'edit']"
    model="customers"
    :id="$customer->id"
/>
