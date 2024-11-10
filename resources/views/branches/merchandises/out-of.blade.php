<section
    id="outOf"
    class="mx-3 m-lr-0"
    x-cloak
    x-show="!isOnHand"
    x-transition
>
    <div class="box radius-top-0">
        <x-common.client-datatable>
            <x-slot name="headings">
                <th><abbr> # </abbr></th>
                <th><abbr> Product </abbr></th>
                <th><abbr> Code </abbr></th>
                <th><abbr> Category </abbr></th>
                <th><abbr> Actions </abbr></th>
            </x-slot>
            <x-slot name="body">
                @foreach ($outOfStockMerchandises as $product)
                    <tr>
                        <td> {{ $loop->index + 1 }} </td>
                        <td class="is-capitalized"> {{ $product->name }} </td>
                        <td class="is-capitalized"> {{ $product->code ?? 'N/A' }} </td>
                        <td class="is-capitalized"> {{ $product->productCategory->name ?? 'N/A' }} </td>
                        <td>
                            <a
                                href="{{ route('warehouses-products', [$product->id, $warehouse->id]) }}"
                                data-title="View Product History"
                            >
                                <span class="tag is-white btn-green is-outlined is-small text-green has-text-weight-medium">
                                    <span class="icon">
                                        <i class="fas fa-history"></i>
                                    </span>
                                    <span>
                                        View History
                                    </span>
                                </span>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        </x-common.client-datatable>
    </div>
</section>
