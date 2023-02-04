@if (isFeatureEnabled('Price Management'))
    @can('Update Price')
        <x-common.button
            tag="a"
            href="{{ route('prices.change_status', $price->id) }}"
            mode="button"
            data-title="Change status"
            icon="fas fa-toggle-{{ $price->isActive() ? 'on' : 'off' }}"
            class="text-green has-text-weight-medium is-not-underlined is-small px-2 py-0 is-transparent-color"
        />
    @endcan
@endif

<x-common.action-buttons
    :buttons="['delete']"
    model="prices"
    :id="$price->id"
/>
