<section id="outOf" class="mx-3 m-lr-0 is-hidden">
    <div class="box radius-top-0">
        <div class="table-container">
            <table class="table is-hoverable is-fullwidth is-size-7">
                <thead>
                    <tr>
                        <th><abbr> # </abbr></th>
                        <th><abbr> Product </abbr></th>
                        <th><abbr> Code </abbr></th>
                        <th><abbr> Category </abbr></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($outOfStockMerchandises as $product)
                        <tr>
                            <td> {{ $loop->index + 1 }} </td>
                            <td class="is-capitalized"> {{ $product->name ?? 'N/A' }} </td>
                            <td class="is-capitalized"> {{ $product->code ?? 'N/A' }} </td>
                            <td class="is-capitalized"> {{ $product->productCategory->name ?? 'N/A' }} </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
