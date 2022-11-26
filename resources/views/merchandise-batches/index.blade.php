@extends('layouts.app')

@section('title', 'History of expired product batches')

@section('content')
    @if ($merchandiseBatches->isNotEmpty())
        <x-common.content-wrapper>
            <x-content.header title="Product Batches Expired After {{ userCompany()->expired_in }} {{ userCompany()->expiry_time_type }}" />
            <x-content.footer>
                <x-common.bulma-table>
                    <x-slot name="headings">
                        <th class="pl-5"> # </th>
                        <th> Product </th>
                        <th> Batch No </th>
                        <th> Quantity </th>
                        <th> Expiry Date </th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($merchandiseBatches as $merchandiseBatch)
                            <tr>
                                <td class="pl-5"> {{ $loop->index + 1 }} </td>
                                <td>
                                    {{ $merchandiseBatch->name }}
                                </td>
                                <td>
                                    {{ $merchandiseBatch->batch_no }}
                                </td>
                                <td>
                                    {{ $merchandiseBatch->quantity }}
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
