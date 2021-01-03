<section id="onHand" class="mx-3 m-lr-0 is-hidden">
    <div class="box radius-top-0">
        <div class="table-container">
            <table class="table is-hoverable is-fullwidth is-size-7">
                <thead>
                    <tr>
                        <th><abbr> # </abbr></th>
                        <th><abbr> Product </abbr></th>
                        <th><abbr> Code </abbr></th>
                        <th><abbr> Category </abbr></th>
                        <th class="has-text-right text-green"><abbr> On Hand </abbr></th>
                        <th class="text-gold"><abbr> Level </abbr></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($onHandMerchandises as $merchandise)
                        <tr>
                            <td> {{ $loop->index + 1 }} </td>
                            <td class="is-capitalized"> {{ $merchandise->product->name ?? 'N/A' }} </td>
                            <td class="is-capitalized"> {{ $merchandise->product->code ?? 'N/A' }} </td>
                            <td class="is-capitalized"> {{ $merchandise->product->productCategory->name ?? 'N/A' }} </td>
                            <td class="has-text-right">
                                <span class="tag is-small bg-green has-text-white">
                                    {{ $merchandise->total_on_hand }}
                                    {{ $merchandise->product->unit_of_measurement }}
                                </span>
                            </td>
                            <td class="is-capitalized"> 
                                <span class="tag is-small bg-gold has-text-white">
                                    <span class="icon">
                                        <i class="fas fa-check-circle"></i>
                                    </span>
                                    <span>
                                        Sufficient
                                    </span>
                                </span>    
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
