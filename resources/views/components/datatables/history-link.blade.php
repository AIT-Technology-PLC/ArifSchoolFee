@if (isset($amount))
    <span class="is-hidden"> {{ number_format($amount, 2, '.', '') }} </span>
@endif


@if (isset($productId) && isset($warehouseId))
    <a
        href="/history/products/{{ $productId }}/warehouses/{{ $warehouseId }}"
        data-title="View Product History"
    >
        @if (isset($amount) && isset($min_on_hand) && isset($unit))
            <span @class([
                'tag is-small',
                'btn-green' => $amount > 0 && $amount > $min_on_hand && !isset($expired),
                'btn-gold' => $amount > 0 && $amount <= $min_on_hand && !isset($expired),
                'btn-purple' => $amount == 0 || isset($expired),
                'is-outlined has-text-white',
            ])>
                {{ number_format($amount, 2, '.', '') }} {{ $unit }}
            </span>
        @else
            <span class='tag is-small btn-purple is-outlined'>
                Track History
            </span>
        @endif
    </a>
@else
    @if (isset($amount) && isset($min_on_hand) && isset($unit))
        <span @class([
            'tag is-small',
            'btn-green' => $amount > 0 && $amount > $min_on_hand && !isset($expired),
            'btn-gold' => $amount > 0 && $amount <= $min_on_hand && !isset($expired),
            'btn-purple' => $amount == 0 || isset($expired),
            'is-outlined has-text-white',
        ])>
            {{ number_format($amount, 2, '.', '') }} {{ $unit }}
        </span>
    @else
        <span class='tag is-small btn-purple is-outlined'>
            Track History
        </span>
    @endif
@endif
