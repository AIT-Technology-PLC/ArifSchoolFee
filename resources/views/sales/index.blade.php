@extends('layouts.app')

@section('title')
    Sales Management
@endsection

@section('content')
    <div class="columns is-marginless">
        <div class="column is-6">
            <div class="box text-green">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column has-text-centered is-paddingless">
                        <span class="icon is-large is-size-1">
                            <i class="fas fa-tags"></i>
                        </span>
                    </div>
                    <div class="column is-paddingless">
                        <div class="is-size-3 has-text-weight-bold">
                            {{ $totalSales }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            Total Sales
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="column is-6">
            <div class="box text-purple">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column is-paddingless has-text-centered">
                        <div class="is-uppercase is-size-7">
                            Create new sale order, track order and expense
                        </div>
                        <div class="is-size-3">
                            <a href="{{ route('sales.create') }}" class="button bg-purple has-text-white has-text-weight-medium is-size-7 px-5 py-4 mt-3">
                                <span class="icon">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span>
                                    Create New Sale
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                Sale Management
            </h1>
        </div>
        <div class="box radius-top-0">
            @include('components.deleted_message', ['model' => 'Sale'])
            <div class="table-container">
                <table id="table_id" class="is-hoverable is-size-7">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th class="text-gold"><abbr> Receipt No </abbr></th>
                            <th class="text-purple"><abbr> Payment Method </abbr></th>
                            <th class="has-text-centered"><abbr> Total Items </abbr></th>
                            <th class="has-text-right text-green"><abbr> Total Price </abbr></th>
                            <th class="has-text-right"><abbr> Sold on </abbr></th>
                            @can('delete', $sales->first())
                                <th><abbr> Added By </abbr></th>
                                <th><abbr> Edited By </abbr></th>
                            @endcan
                            <th><abbr> Actions </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sales as $sale)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized">
                                    <span class="tag bg-gold has-text-white">
                                        {{ $sale->receipt_no ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="is-capitalized">
                                    <span class="tag bg-purple has-text-white">
                                        {{ $sale->payment_type ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="has-text-centered has-text-weight-bold">
                                    {{ $sale->sale_details_count ?? 'N/A' }}
                                </td>
                                <td class="has-text-right">
                                    <span class="tag is-small bg-green has-text-white">
                                        {{ $sale->company->currency }}.
                                        {{ $sale->totalSalePrice }}
                                    </span>
                                </td>
                                <td class="has-text-right">
                                    {{ $sale->sold_on->toFormattedDateString() }}
                                </td>
                                @can('delete', $sale)
                                    <td> {{ $sale->createdBy->name ?? 'N/A' }} </td>
                                    <td> {{ $sale->updatedBy->name ?? 'N/A' }} </td>
                                @endcan
                                <td>
                                    <a class="is-block" href="{{ route('sales.show', $sale->id) }}" data-title="View Details">
                                        <span class="tag mb-3 is-white btn-purple is-outlined is-small text-green has-text-weight-medium">
                                            <span class="icon">
                                                <i class="fas fa-info-circle"></i>
                                            </span>
                                            <span>
                                                Details
                                            </span>
                                        </span>
                                    </a>
                                    <a class="is-block" href="{{ route('sales.edit', $sale->id) }}" data-title="Modify Sales Data">
                                        <span class="tag mb-3 is-white btn-green is-outlined is-small text-green has-text-weight-medium">
                                            <span class="icon">
                                                <i class="fas fa-pen-square"></i>
                                            </span>
                                            <span>
                                                Edit
                                            </span>
                                        </span>
                                    </a>
                                    <span class="is-block">
                                        @include('components.delete_button', ['model' => 'sales',
                                        'id' => $sale->id])
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
