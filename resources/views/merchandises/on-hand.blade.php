<section id="onHand" class="mx-3 m-lr-0">
    <div class="box radius-top-0">
        @include('components.deleted_message', ['model' => 'Merchandise'])
        <div class="table-container">
            <table id="table_id" class="is-hoverable is-size-7 display">
                <thead>
                    <tr>
                        <th><abbr> # </abbr></th>
                        <th><abbr> Product </abbr></th>
                        <th><abbr> Code </abbr></th>
                        <th><abbr> Warehouse </abbr></th>
                        <th class="has-text-right"><abbr> Quantity Received </abbr></th>
                        <th class="has-text-right text-green"><abbr> On Hand </abbr></th>
                        <th class="has-text-right text-purple"><abbr> Damaged </abbr></th>
                        <th class="has-text-right text-gold"><abbr> Transfer </abbr></th>
                        <th class="has-text-right text-blue"><abbr> Sold </abbr></th>
                        <th class="has-text-right"><abbr> Returns </abbr></th>
                        <th class="has-text-right"><abbr> Added On </abbr></th>
                        <th class="has-text-right"><abbr> Expiry Date </abbr></th>
                        @can('delete', $onHandMerchandises->first())
                            <th><abbr> Added By </abbr></th>
                            <th><abbr> Edited By </abbr></th>
                        @endcan
                        <th><abbr> Actions </abbr></th>
                    </tr>
                </thead>
                <tbody class="list">
                    @foreach ($onHandMerchandises as $merchandise)
                        <tr>
                            <td> {{ $loop->index + 1 }} </td>
                            <td class="is-capitalized name"> {{ $merchandise->product->name ?? 'N/A' }} </td>
                            <td class="is-capitalized code"> {{ $merchandise->product->code ?? 'N/A' }} </td>
                            <td class="is-capitalized"> {{ $merchandise->warehouse->name ?? 'N/A' }} </td>
                            <td class="has-text-right">
                                {{ $merchandise->total_received }}
                                {{ $merchandise->product->unit_of_measurement }}
                            </td>
                            <td class="has-text-right">
                                <span class="tag is-small bg-green has-text-white onHand">
                                    {{ $merchandise->total_on_hand }}
                                    {{ $merchandise->product->unit_of_measurement }}
                                </span>
                            </td>
                            <td class="has-text-right">
                                <span class="tag is-small bg-purple has-text-white">
                                    {{ $merchandise->total_broken }}
                                    {{ $merchandise->product->unit_of_measurement }}
                                </span>
                            </td>
                            <td class="has-text-right">
                                <span class="tag is-small bg-gold has-text-white">
                                    {{ $merchandise->total_transfer }}
                                    {{ $merchandise->product->unit_of_measurement }}
                                </span>
                            </td>
                            <td class="has-text-right">
                                <span class="tag is-small bg-blue has-text-white">
                                    {{ $merchandise->total_sold }}
                                    {{ $merchandise->product->unit_of_measurement }}
                                </span>
                            </td>
                            <td class="has-text-right">
                                {{ $merchandise->total_returns }}/{{ $merchandise->total_sold }}
                                {{ $merchandise->product->unit_of_measurement }}
                            </td>
                            <td class="has-text-right"> {{ $merchandise->created_at->toFormattedDateString() }} </td>
                            <td class="has-text-right"> {{ $merchandise->expires_on ? $merchandise->expires_on->toFormattedDateString() : 'N/A' }} </td>
                            @can('delete', $merchandise)
                                <td> {{ $merchandise->createdBy->name ?? 'N/A' }} </td>
                                <td> {{ $merchandise->updatedBy->name ?? 'N/A' }} </td>
                            @endcan
                            <td>
                                <a class="is-block" href="{{ route('merchandises.edit', $merchandise->id) }}" data-title="Manage Returned or Damaged Product">
                                    <span class="tag mb-3 is-white btn-green is-outlined is-small text-green has-text-weight-medium">
                                        <span class="icon">
                                            <i class="fas fa-toolbox"></i>
                                        </span>
                                        <span>
                                            Manage
                                        </span>
                                    </span>
                                </a>
                                <span class="is-block">
                                    @include('components.delete_button', ['model' => 'merchandises',
                                    'id' => $merchandise->id])
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
