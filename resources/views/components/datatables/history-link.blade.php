@if (isset($amount))
    <span
        class="is-hidden"
        data-sort="{{ $amount }}"
    ></span>
@endif


@if (isset($productId) && isset($warehouseId))
    <a
        href="/history/products/{{ $productId }}/warehouses/{{ $warehouseId }}"
        data-title="View Product History"
    >
        @if (isset($amount) && isset($reorderQuantity) && isset($unit))
            <span @class([
                'tag is-small',
                'btn-green' =>
                    $amount > 0 &&
                    $amount >
                        (is_array($reorderQuantity) &&
                        array_key_exists($warehouseId, $reorderQuantity)
                            ? $reorderQuantity[$warehouseId]
                            : $reorderQuantity) &&
                    !isset($expired),
                'btn-gold' =>
                    $amount > 0 &&
                    $amount <=
                        (is_array($reorderQuantity) &&
                        array_key_exists($warehouseId, $reorderQuantity)
                            ? $reorderQuantity[$warehouseId]
                            : $reorderQuantity) &&
                    !isset($expired),
                'btn-purple' => $amount == 0 || isset($expired),
                'is-outlined has-text-white',
            ])>
                {{ quantity($amount, $unit) }}
            </span>
        @else
            <span class='tag is-small btn-purple is-outlined'>
                Track History
            </span>
        @endif
    </a>
@else
    @if (isset($amount) && isset($reorderQuantity) && isset($unit))
        <span @class([
            'tag is-small',
            'btn-green' =>
                $amount > 0 &&
                $amount >
                    (is_array($reorderQuantity) &&
                    array_key_exists($warehouseId, $reorderQuantity)
                        ? $reorderQuantity[$warehouseId]
                        : $reorderQuantity) &&
                !isset($expired),
            'btn-gold' =>
                $amount > 0 &&
                $amount <=
                    (is_array($reorderQuantity) &&
                    array_key_exists($warehouseId, $reorderQuantity)
                        ? $reorderQuantity[$warehouseId]
                        : $reorderQuantity) &&
                !isset($expired),
            'btn-purple' => $amount == 0 || isset($expired),
            'is-outlined has-text-white',
        ])>
            {{ quantity($amount, $unit) }}
        </span>
    @else
        <span class='tag is-small btn-purple is-outlined'>
            Track History
        </span>
    @endif
@endif
