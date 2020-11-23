@extends('layouts.app')

@section('title')
    Purchase Management
@endsection

@section('content')
    <div class="columns is-marginless">
        <div class="column is-6">
            <div class="box text-green">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column has-text-centered is-paddingless">
                        <span class="icon is-large is-size-1">
                            <i class="fas fa-shopping-bag"></i>
                        </span>
                    </div>
                    <div class="column is-paddingless">
                        <div class="is-size-3 has-text-weight-bold">
                            {{ $totalPurchases }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            Total Purchases
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
                            Create new purchase order, track order and expense
                        </div>
                        <div class="is-size-3">
                            <a href="{{ route('purchases.create') }}" class="button bg-purple has-text-white has-text-weight-medium is-size-7 px-5 py-4 mt-3">
                                <span class="icon">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span>
                                    Create New Purchase
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
                Purchase Management
            </h1>
        </div>
        <div class="box radius-top-0">
            <div class="table-container">
                <table class="table is-hoverable is-fullwidth is-size-7">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th><abbr> Product </abbr></th>
                            <th><abbr> Supplier </abbr></th>
                            <th><abbr> Quantity </abbr></th>
                            <th><abbr> Price </abbr></th>
                            <th><abbr> Shipping Line </abbr></th>
                            <th><abbr> Payment Status </abbr></th>
                            <th><abbr> Shipped On </abbr></th>
                            <th><abbr> Delivered On </abbr></th>
                            @can('delete', $purchases->first())
                                <th><abbr> Added By </abbr></th>
                                <th><abbr> Edited By </abbr></th>
                            @endcan
                            <th><abbr> Actions </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchases as $purchase)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized">
                                    {{ $purchase->product->name ?? 'N/A' }}
                                </td>
                                <td class="is-capitalized">
                                    {{ $purchase->supplier->company_name ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $purchase->total_quantity . ' ' . $purchase->product->unit_of_measurement ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $purchase->company->currency . ' ' . $purchase->total_price ?? 'N/A' }}
                                </td>
                                <td class="is-capitalized">
                                    {{ $purchase->shipping_line ?? 'N/A' }}
                                </td>
                                <td class="is-capitalized">
                                    {{ $purchase->payment_status ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $purchase->shipped_at ? $purchase->shipped_at->toFormattedDateString() : 'Not Shipped' }}
                                </td>
                                <td> 
                                    {{ $purchase->delivered_at ? $purchase->delivered_at->toFormattedDateString() : 'Not Delivered' }} 
                                </td>
                                @can('delete', $purchase)
                                    <td> {{ $purchase->createdBy->name ?? 'N/A' }} </td>
                                    <td> {{ $purchase->updatedBy->name ?? 'N/A' }} </td>
                                @endcan
                                <td>
                                    <a href="{{ route('purchases.edit', $purchase->id) }}" title="Modify Purchase Data" class="text-green is-size-6-5">
                                        <span class="icon">
                                            <i class="fas fa-pen-square"></i>
                                        </span>
                                        <span>
                                            Edit
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
@endsection
