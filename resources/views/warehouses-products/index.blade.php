@extends('layouts.app')

@section('title', 'History of ' . $product->name . ' in ' . $warehouse->name)

@section('content')
    @if (!isset($expired))
        <x-common.content-wrapper>
            <x-content.header title="History of {{ $product->name }} in {{ $warehouse->name }}" />
            <x-content.footer>
                {{ $dataTable->table() }}
            </x-content.footer>
        </x-common.content-wrapper>
    @endif
    @if ($merchandiseBatches->isNotEmpty())
        <x-common.content-wrapper>
            <x-content.header title="{{ $product->name }} in {{ $warehouse->name }} by Batches" />
            <x-content.footer>
                <x-common.bulma-table>
                    <x-slot name="headings">
                        <th class="pl-5"> # </th>
                        <th> Batch No </th>
                        <th> Quantity </th>
                        <th> Expiry Date </th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($merchandiseBatches as $merchandiseBatch)
                            <tr>
                                <td class="pl-5"> {{ $loop->index + 1 }} </td>
                                <td>
                                    {{ $merchandiseBatch->batch_no }}
                                </td>
                                <td>
                                    {{ $merchandiseBatch->quantity }} {{ $product->unit_of_measurement }}
                                </td>
                                <td>
                                    {{ $merchandiseBatch->expiry_date?->toFormattedDateString() }}
                                </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.bulma-table>
            </x-content.footer>
        </x-common.content-wrapper>
    @endif
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
