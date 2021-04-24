<section id="onHand" class="mx-3 m-lr-0">
    <div class="box radius-top-0">
        <div>
            <table id="table_id" class="is-hoverable is-size-7 display nowrap" data-date="[]" data-numeric="[3,4,5]">
                <thead>
                    <tr>
                        <th id="firstTarget"><abbr> # </abbr></th>
                        <th><abbr> Product </abbr></th>
                        <th><abbr> Category </abbr></th>
                        @foreach ($warehouses as $warehouse)
                            <th class="has-text-right text-green is-capitalized"><abbr> {{ $warehouse->name }} </abbr></th>
                        @endforeach
                        <th class="has-text-right text-green is-capitalized"><abbr> Total Balance </abbr></th>
                    </tr>
                </thead>
                <tbody class="list">
                    @foreach ($onHandMerchandiseProducts as $product)
                        <tr>
                            <td> {{ $loop->index + 1 }} </td>
                            <td class="is-capitalized name">
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
                                    <span class="tag is-small btn-green is-outline">
                                        {{ $merchandise->getTotalOnHandAmount($onHandMerchandises, $product->id, $warehouse->id) }}
                                        {{ $product->unit_of_measurement }}
                                    </span>
                                </td>
                            @endforeach
                            <td class="has-text-right">
                                <span class="tag is-small bg-green has-text-white">
                                    {{ $merchandise->getProductTotalBalance($onHandMerchandises, $product->id) }}
                                    {{ $product->unit_of_measurement }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
