<section id="onHand" class="mx-3 m-lr-0">
    <div class="box radius-top-0">
        <div>
            <table class="regular-datatable is-hoverable is-size-7 display nowrap" data-date="[]" data-numeric="[3,4,5]">
                <thead>
                    <tr>
                        <th><abbr> # </abbr></th>
                        <th><abbr> Product </abbr></th>
                        <th><abbr> Category </abbr></th>
                        <th class="has-text-right text-green"><abbr> Available </abbr></th>
                        <th class="has-text-right text-green"><abbr> Reserved </abbr></th>
                        <th class="has-text-right text-green"><abbr> On Hand </abbr></th>
                        <th><abbr> Level </abbr></th>
                        <th><abbr> Actions </abbr></th>
                    </tr>
                </thead>
                <tbody class="list">
                    @foreach ($onHandMerchandises as $merchandise)
                        <tr>
                            <td> {{ $loop->index + 1 }} </td>
                            <td class="is-capitalized name">
                                {{ $merchandise->product->name ?? 'N/A' }}
                                @if ($merchandise->product->code)
                                    <span class="has-text-grey has-has-text-weight-bold">
                                        -
                                        {{ $merchandise->product->code }}
                                    </span>
                                @endif
                            </td>
                            <td class="is-capitalized"> {{ $merchandise->product->productCategory->name ?? 'N/A' }} </td>
                            <td class="has-text-right">
                                <span class="tag is-small btn-green is-outlined has-text-white">
                                    {{ $merchandise->available }}
                                    {{ $merchandise->product->unit_of_measurement }}
                                </span>
                            </td>
                            <td class="has-text-right">
                                <span class="tag is-small btn-green is-outlined has-text-white">
                                    {{ $merchandise->reserved }}
                                    {{ $merchandise->product->unit_of_measurement }}
                                </span>
                            </td>
                            <td class="has-text-right">
                                <span class="tag is-small btn-green is-outlined has-text-white">
                                    {{ $merchandise->on_hand }}
                                    {{ $merchandise->product->unit_of_measurement }}
                                </span>
                            </td>
                            <td class="is-capitalized">
                                @if ($merchandise->product->isProductLimited($merchandise->on_hand))
                                    <span class="tag is-small bg-gold has-text-white">
                                        <span class="icon">
                                            <i class="fas fa-exclamation-circle"></i>
                                        </span>
                                        <span>
                                            Limited
                                        </span>
                                    </span>
                                @else
                                    <span class="tag is-small bg-blue has-text-white">
                                        <span class="icon">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                        <span>
                                            Sufficient
                                        </span>
                                    </span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('warehouses-products', [$warehouse->id, $merchandise->product->id]) }}" data-title="View Product History">
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
                </tbody>
            </table>
        </div>
    </div>
</section>
