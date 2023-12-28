@if ($product->prices->isNotEmpty())
    @if (isFeatureEnabled('Price Management'))
        @can('Read Price')
            <x-common.button
                tag="a"
                href="{{ route('products.prices.index', $product->id) }}"
                mode="button"
                data-title="View details"
                icon="fas fa-info-circle"
                class="text-green has-text-weight-medium is-not-underlined is-small px-2 py-0 is-transparent-color"
            />
        @endcan
    @endif

    <x-common.action-buttons
        :buttons="['edit']"
        model="prices"
        :id="$product->prices()->first()->id"
    />
@endif
