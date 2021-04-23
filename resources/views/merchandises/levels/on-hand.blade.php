<section id="onHand" class="mx-3 m-lr-0">
    <div class="box radius-top-0">
        <div>
            <table id="table_id" class="is-hoverable is-size-7 display nowrap" data-date="[]" data-numeric="[4]">
                <thead>
                    <tr>
                        <th id="firstTarget"><abbr> # </abbr></th>
                        <th><abbr> Product </abbr></th>
                        <th><abbr> Code </abbr></th>
                        <th><abbr> Category </abbr></th>
                        @foreach ($warehouses as $warehouse)
                            <th class="has-text-right text-green is-capitalized"><abbr> {{ $warehouse->name }} </abbr></th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="list">
                    @foreach ($onHandMerchandiseProducts as $product)
                        <tr>
                            <td> {{ $loop->index + 1 }} </td>
                            <td class="is-capitalized name"> {{ $product->name ?? 'N/A' }} </td>
                            <td class="is-capitalized code"> {{ $product->code ?? 'N/A' }} </td>
                            <td class="is-capitalized"> {{ $product->productCategory->name ?? 'N/A' }} </td>
                            @foreach ($warehouses as $warehouse)
                                <td class="has-text-right">
                                    <span class="tag is-small bg-green has-text-white">
                                        {{ $onHandMerchandises->where('product_id', $product->id)->where('warehouse_id', $warehouse->id)->first()->total_on_hand ?? '0.00' }}
                                        {{ $product->unit_of_measurement }}
                                    </span>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
