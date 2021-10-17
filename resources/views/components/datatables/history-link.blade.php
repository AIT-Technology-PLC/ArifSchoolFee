@if (isset($amount))
    <span class="is-hidden"> {{ number_format($amount, 2, '.', '') }} </span>
@endif

<a href="/history/products/{{ $productId }}/warehouses/{{ $warehouseId }}" data-title="View Product History">
    <span class='tag is-small btn-green is-outlined'>
        @if (isset($amount))
            {{ number_format($amount, 2, '.', '') }} {{ $unit }}
        @else
            Track History
        @endif
    </span>
</a>
