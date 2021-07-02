<section id="outOf" class="mx-3 m-lr-0 is-hidden">
    <div class="box radius-top-0">
        <div class="table-container">
            <table class="table is-hoverable is-fullwidth is-size-7">
                <thead>
                    <tr>
                        <th><abbr> # </abbr></th>
                        <th><abbr> Product </abbr></th>
                        <th><abbr> Category </abbr></th>
                        @foreach ($warehouses as $warehouse)
                            <th class="has-text-right text-green is-capitalized"><abbr> {{ $warehouse->name }} </abbr></th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($outOfStockMerchandiseProducts as $product)
                        <tr>
                            <td> {{ $loop->index + 1 }} </td>
                            <td class="is-capitalized">
                                {{ $product->name ?? 'N/A' }}
                                @if ($product->code)
                                    <span class="has-text-grey has-has-text-weight-bold">
                                        -
                                        {{ $product->code }}
                                    </span>
                                @endif
                            </td>
                            <td class="is-capitalized"> {{ $product->productCategory->name ?? 'N/A' }} </td>
                            @foreach ($warehouses as $warehouse)
                                <td class="has-text-right">
                                    <a href="{{ route('warehouses-products', [$warehouse->id, $product->id]) }}" data-title="View Product History">
                                        <span class="tag is-small btn-green is-outlined">
                                            Track History
                                        </span>
                                    </a>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
