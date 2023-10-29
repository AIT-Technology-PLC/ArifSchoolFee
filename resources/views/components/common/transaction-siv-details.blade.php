@if (isFeatureEnabled('Siv Management') && $sivs)
    <x-common.content-wrapper class="mt-5">
        <x-content.header title="Store Issue Vouchers" />
        <x-content.footer>
            <x-common.client-datatable>
                <x-slot name="headings">
                    <th> # </th>
                    <th> Issued on </th>
                    <th class="has-text-centered"> Siv No </th>
                    <th> Status </th>
                    <th> Product </th>
                    <th> Quantity </th>
                    <th> From </th>
                </x-slot>
                <x-slot name="body">
                    @foreach ($sivs as $siv)
                        @foreach ($siv->sivDetails as $sivDetail)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized">
                                    {{ $sivDetail->siv->issued_on->toFormattedDateString() }}
                                </td>
                                <td class="is-capitalized has-text-centered">
                                    <a
                                        class="text-blue has-text-weight-medium"
                                        href="{{ route('sivs.show', $sivDetail->siv->id) }}"
                                    >
                                        {{ $sivDetail->siv->code }}
                                    </a>
                                </td>
                                <td>
                                    @include('components.datatables.siv-status', ['siv' => $sivDetail->siv])
                                </td>
                                <td> {{ $sivDetail->product->name }} </td>
                                <td data-sort="{{ $sivDetail->quantity }}"> {{ quantity($sivDetail->quantity, $sivDetail->product->unit_of_measurement) }} </td>
                                <td> {{ $sivDetail->warehouse->name }} </td>
                            </tr>
                        @endforeach
                    @endforeach
                </x-slot>
            </x-common.client-datatable>
        </x-content.footer>
    </x-common.content-wrapper>
@endif
