@if (isset($amount))
    <span class="is-hidden"> {{ number_format($amount, 2, '.', '') }} </span>
@endif

<a
    href="/history/products/{{ $productId }}/warehouses/{{ $warehouseId }}"
    data-title="View Product History"
>
    <span class='tag is-small @if ($amount > $min_on_hand) btn-green is-outlined @elseif($amount == 0) btn-purple is-outlined @else btn bg-gold has-text-white @endif'>
        @if (isset($amount))
            {{ number_format($amount, 2, '.', '') }} {{ $unit }}
        @else
            Track History
        @endif
    </span>
</a>
