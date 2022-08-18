@extends('layouts.app')

@section('title', 'History of ' . $product->name . ' in ' . $warehouse->name)

@section('content')
    <x-common.content-wrapper>
        @if (count($merchandiseBatches) > 0)
            <x-content.header title="Batch History of {{ $product->name }} in {{ $warehouse->name }}">
            </x-content.header>
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
        @endif
        <x-content.header title="History of {{ $product->name }} in {{ $warehouse->name }}">
        </x-content.header>
        <x-content.footer>
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
