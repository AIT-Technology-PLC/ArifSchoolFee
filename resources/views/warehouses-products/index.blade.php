@extends('layouts.app')

@section('title', 'History of ' . $product->name . ' in ' . $warehouse->name)

@section('content')
    @if ($merchandiseBatches->isNotEmpty())
        <x-common.content-wrapper>
            <x-content.header
                title="{{ $product->name }} Batches in {{ $warehouse->name }}"
                is-mobile
            >
            </x-content.header>
            <x-content.footer>
                <x-common.fail-message :message="session('failedMessage')" />
                <x-common.client-datatable>
                    <x-slot name="headings">
                        <th class="pl-5"> # </th>
                        <th> Batch No </th>
                        <th> Expiry Date </th>
                        <th> Received Quantity </th>
                        <th> Available Quantity </th>
                        <th> Actions </th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($merchandiseBatches as $merchandiseBatch)
                            <tr>
                                <td class="pl-5"> {{ $loop->index + 1 }} </td>
                                <td>
                                    {{ $merchandiseBatch->batch_no }}
                                </td>
                                <td data-sort="{{ $merchandiseBatch->expires_on?->toDateString() }}">
                                    <span @class([
                                        'tag',
                                        'bg-lightpurple text-purple' => $merchandiseBatch->expires_on?->isPast(),
                                        'bg-lightgreen text-green' => $merchandiseBatch->expires_on?->isFuture(),
                                    ])>
                                        {{ $merchandiseBatch->expires_on?->toFormattedDateString() ?? 'N/A' }}
                                    </span>
                                </td>
                                <td data-sort="{{ $merchandiseBatch->received_quantity }}">
                                    {{ $merchandiseBatch->received_quantity }} {{ $product->unit_of_measurement }}
                                </td>
                                <td data-sort="{{ $merchandiseBatch->quantity }}">
                                    {{ $merchandiseBatch->quantity }} {{ $product->unit_of_measurement }}
                                </td>
                                <td>
                                    @if ($merchandiseBatch->isExpired() && $merchandiseBatch->isAvailable() && isFeatureEnabled('Damage Management') && isFeatureEnabled('Batch Management'))
                                        @can(['Create Damage', 'Damage Merchandise Batch'])
                                            <x-common.button
                                                tag="a"
                                                href="{{ route('merchandise-batches.convert_to_damage', $merchandiseBatch->id) }}"
                                                mode="button"
                                                icon="fas fa-bolt"
                                                label="Convert to Damage"
                                                class="btn-green is-outlined has-text-weight-medium is-not-underlined is-small px-2 py-0 is-transparent-color"
                                            />
                                        @endcan
                                    @else
                                        No Actions
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </x-common.content-wrapper>
    @endif

    <x-common.content-wrapper>
        <x-content.header title="History of {{ $product->name }} in {{ $warehouse->name }}" />
        <x-content.footer>
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
