<section
    id="onHand"
    class="mx-3 m-lr-0"
    x-cloak
    x-show="isOnHand"
    x-transition
>
    <div class="box radius-top-0">
        <x-content.footer>
            <x-common.client-datatable>
                <x-slot name="headings">
                    <th><abbr> # </abbr></th>
                    <th><abbr> Product </abbr></th>
                    <th><abbr> Category </abbr></th>
                    @can('Read Available Inventory')
                        <th class="has-text-right text-green"><abbr> Available </abbr></th>
                    @endcan
                    @if (isFeatureEnabled('Reservation Management'))
                        @can('Read Reserved Inventory')
                            <th class="has-text-right text-green"><abbr> Reserved </abbr></th>
                        @endcan
                    @endif
                    @if (userCompany()->plan->isPremium())
                        @can('Read Work In Process Inventory')
                            <th class="has-text-right text-green"><abbr> Work In Process </abbr></th>
                        @endcan
                    @endif
                    @can('Read On Hand Inventory')
                        <th class="has-text-right text-green"><abbr> On Hand </abbr></th>
                    @endcan
                    <th><abbr> Level </abbr></th>
                    <th><abbr> Actions </abbr></th>
                </x-slot>
                <x-slot name="body">
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
                            @can('Read Available Inventory')
                                <td class="has-text-right">
                                    <span class="tag is-small btn-green is-outlined has-text-white">
                                        {{ $merchandise->available }}
                                        {{ $merchandise->product->unit_of_measurement }}
                                    </span>
                                </td>
                            @endcan
                            @if (isFeatureEnabled('Reservation Management'))
                                @can('Read Reserved Inventory')
                                    <td class="has-text-right">
                                        <span class="tag is-small btn-green is-outlined has-text-white">
                                            {{ $merchandise->reserved }}
                                            {{ $merchandise->product->unit_of_measurement }}
                                        </span>
                                    </td>
                                @endcan
                            @endif
                            @if (userCompany()->plan->isPremium())
                                @can('Read Work In Process Inventory')
                                    <td class="has-text-right">
                                        <span class="tag is-small btn-green is-outlined has-text-white">
                                            {{ $merchandise->wip }}
                                            {{ $merchandise->product->unit_of_measurement }}
                                        </span>
                                    </td>
                                @endcan
                            @endif
                            @can('Read On Hand Inventory')
                                <td class="has-text-right">
                                    <span class="tag is-small btn-green is-outlined has-text-white">
                                        {{ $merchandise->on_hand }}
                                        {{ $merchandise->product->unit_of_measurement }}
                                    </span>
                                </td>
                            @endcan
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
                                <a
                                    href="{{ route('warehouses-products', [$merchandise->product->id, $warehouse->id]) }}"
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
        </x-content.footer>
    </div>
</section>
