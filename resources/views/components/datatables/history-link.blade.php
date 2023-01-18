@if (isset($amount))
    <span class="is-hidden"> {{ number_format($amount, 2, '.', '') }} </span>
@endif

@if (isset($productId) && isset($warehouseId) && isset($expired))
    <a
        href="/history/products/{{ $productId }}/warehouses/{{ $warehouseId }}/{{ $expired }}"
        data-title="View Expired Batch History"
    >

    <span class='tag is-small  btn-purple is-outlined'>
        {{ number_format($amount, 2, '.', '') }} {{ $unit }}
    </span>
@endif

@if (isset($productId) && isset($warehouseId) && !isset($expired))
    <a
        href="/history/products/{{ $productId }}/warehouses/{{ $warehouseId }}"
        data-title="View Product History"
    >
@endif

@if (isset($amount) && isset($min_on_hand) && isset($unit) && !isset($expired))
    <span class='tag is-small @if ($amount > $min_on_hand) btn-green is-outlined @elseif($amount == 0) btn-purple is-outlined @else btn bg-gold has-text-white @endif'>
        {{ number_format($amount, 2, '.', '') }} {{ $unit }}
    </span>
@else
@if(!isset($expired))
    <span class='tag is-small btn-purple is-outlined'>
        Track History
    </span>
@endif
@endif

@if (isset($productId) && isset($warehouseId))
    </a>
@endif
