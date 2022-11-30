@if (isset($amount))
    <span class="is-hidden"> {{ number_format($amount, 2, '.', '') }} </span>
@endif

@if (isset($productId) && isset($warehouseId))
    <a
        href="/history/products/{{ $productId }}/warehouses/{{ $warehouseId }}"
        data-title="View Product History"
    >
@endif

@if (isset($amount) && isset($min_on_hand) && isset($unit))
    <span class='tag is-small @if ($amount > $min_on_hand) btn-green is-outlined @elseif($amount == 0) btn-purple is-outlined @else btn bg-gold has-text-white @endif'>
        {{ number_format($amount, 2, '.', '') }} {{ $unit }}
    </span>
@else
    <span class='tag is-small btn-purple is-outlined'>
        Track History
    </span>
@endif

@if (isset($productId) && isset($warehouseId))
    </a>
@endif
