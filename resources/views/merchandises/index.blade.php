@extends('layouts.app')

@section('title')
    Merchandise Inventory
@endsection

@section('content')
    {{-- <div class="columns is-marginless">
        <div class="column is-6">
            <div class="box text-green">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column has-text-centered is-paddingless">
                        <span class="icon is-large is-size-1">
                            <i class="fas fa-th"></i>
                        </span>
                    </div>
                    <div class="column is-paddingless">
                        <div class="is-size-3 has-text-weight-bold">
                            {{ $totalProductsOfCompany ?? 0 }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            Total Products
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
                            Do you want to add new products to your inventory?
                        </div>
                        <div class="is-size-3">
                            <a href="{{ route('products.create') }}" class="button bg-purple has-text-white has-text-weight-medium is-size-7 px-5 py-4 mt-3">
                                <span class="icon">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span>
                                    Create New Product
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                Stock On Hand
            </h1>
        </div>
        <div class="box radius-top-0">
            <div class="table-container">
                <table class="table is-hoverable is-fullwidth is-size-7">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th><abbr> Product </abbr></th>
                            <th><abbr> Warehouse </abbr></th>
                            <th class="has-text-right"><abbr> Quantity Received </abbr></th>
                            <th class="has-text-right text-green"><abbr> On Hand </abbr></th>
                            <th class="has-text-right text-blue"><abbr> Sold </abbr></th>
                            <th class="has-text-right text-purple"><abbr> Damaged </abbr></th>
                            <th class="has-text-right text-gold"><abbr> Returns </abbr></th>
                            <th class="has-text-right"><abbr> Expiry Date </abbr></th>
                            @can('delete', $onHandMerchandises->first())
                                <th><abbr> Added By </abbr></th>
                                <th><abbr> Edited By </abbr></th>
                            @endcan
                            <th class="has-text-centered"><abbr> Actions </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($onHandMerchandises as $merchandise)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized"> {{ $merchandise->product->name ?? 'N/A' }} </td>
                                <td class="is-capitalized"> {{ $merchandise->warehouse->name ?? 'N/A' }} </td>
                                <td class="has-text-right"> 
                                    {{ $merchandise->total_received }} 
                                    {{ $merchandise->product->unit_of_measurement ?? 'N/A' }} 
                                </td>
                                <td class="has-text-right">
                                    <span class="tag is-small bg-green has-text-white">
                                        {{ $merchandise->total_on_hand ?? 'N/A' }}
                                        {{ $merchandise->product->unit_of_measurement }}
                                    </span>
                                </td>
                                <td class="has-text-right">
                                    <span class="tag is-small bg-blue has-text-white">
                                        {{ $merchandise->total_sold ?? 'N/A' }}
                                        {{ $merchandise->product->unit_of_measurement }}
                                    </span>
                                </td>
                                <td class="has-text-right">
                                    <span class="tag is-small bg-purple has-text-white">
                                        {{ $merchandise->total_broken ?? 'N/A' }}
                                        {{ $merchandise->product->unit_of_measurement }}
                                    </span>
                                </td>
                                <td class="has-text-right">
                                    <span class="tag is-small bg-gold has-text-white">
                                        {{ $merchandise->total_returns ?? 'N/A' }}
                                        {{ $merchandise->product->unit_of_measurement }}
                                    </span>
                                </td>
                                <td class="has-text-right"> {{ $merchandise->expires_on ? $merchandise->expires_on->toFormattedDateString() : 'N/A' }} </td>
                                @can('delete', $merchandise)
                                    <td> {{ $merchandise->createdBy->name ?? 'N/A' }} </td>
                                    <td> {{ $merchandise->updatedBy->name ?? 'N/A' }} </td>
                                @endcan
                                <td class="has-text-centered">
                                    <a href="{{ route('merchandises.edit', $merchandise->id) }}" data-title="Manage Returned or Damaged Product">
                                        <span class="tag is-white btn-green is-outlined is-small text-green has-text-weight-medium">
                                            <span class="icon">
                                                <i class="fas fa-toolbox"></i>
                                            </span>
                                            <span>
                                                Manage
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





    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-gold has-text-weight-medium is-size-5">
                Limited Stock
            </h1>
        </div>
        <div class="box radius-top-0">
            <div class="table-container">
                <table class="table is-hoverable is-fullwidth is-size-7">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th><abbr> Product </abbr></th>
                            <th><abbr> Warehouse </abbr></th>
                            <th class="has-text-right"><abbr> Quantity Received </abbr></th>
                            <th class="has-text-right text-green"><abbr> On Hand </abbr></th>
                            <th class="has-text-right text-blue"><abbr> Sold </abbr></th>
                            <th class="has-text-right text-purple"><abbr> Damaged </abbr></th>
                            <th class="has-text-right text-gold"><abbr> Returns </abbr></th>
                            <th class="has-text-right"><abbr> Expiry Date </abbr></th>
                            @can('delete', $limitedMerchandises->first())
                                <th><abbr> Added By </abbr></th>
                                <th><abbr> Edited By </abbr></th>
                            @endcan
                            <th class="has-text-centered"><abbr> Actions </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($limitedMerchandises as $merchandise)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized"> {{ $merchandise->product->name ?? 'N/A' }} </td>
                                <td class="is-capitalized"> {{ $merchandise->warehouse->name ?? 'N/A' }} </td>
                                <td class="has-text-right"> 
                                    {{ $merchandise->total_received }} 
                                    {{ $merchandise->product->unit_of_measurement ?? 'N/A' }} 
                                </td>
                                <td class="has-text-right">
                                    <span class="tag is-small bg-green has-text-white">
                                        {{ $merchandise->total_on_hand ?? 'N/A' }}
                                        {{ $merchandise->product->unit_of_measurement }}
                                    </span>
                                </td>
                                <td class="has-text-right">
                                    <span class="tag is-small bg-blue has-text-white">
                                        {{ $merchandise->total_sold ?? 'N/A' }}
                                        {{ $merchandise->product->unit_of_measurement }}
                                    </span>
                                </td>
                                <td class="has-text-right">
                                    <span class="tag is-small bg-purple has-text-white">
                                        {{ $merchandise->total_broken ?? 'N/A' }}
                                        {{ $merchandise->product->unit_of_measurement }}
                                    </span>
                                </td>
                                <td class="has-text-right">
                                    <span class="tag is-small bg-gold has-text-white">
                                        {{ $merchandise->total_returns ?? 'N/A' }}
                                        {{ $merchandise->product->unit_of_measurement }}
                                    </span>
                                </td>
                                <td class="has-text-right"> {{ $merchandise->expires_on ? $merchandise->expires_on->toFormattedDateString() : 'N/A' }} </td>
                                @can('delete', $merchandise)
                                    <td> {{ $merchandise->createdBy->name ?? 'N/A' }} </td>
                                    <td> {{ $merchandise->updatedBy->name ?? 'N/A' }} </td>
                                @endcan
                                <td class="has-text-centered">
                                    <a href="{{ route('merchandises.edit', $merchandise->id) }}" data-title="Manage Returned or Damaged Product">
                                        <span class="tag is-white btn-green is-outlined is-small text-green has-text-weight-medium">
                                            <span class="icon">
                                                <i class="fas fa-toolbox"></i>
                                            </span>
                                            <span>
                                                Manage
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




    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-purple has-text-weight-medium is-size-5">
                Products Out of Stock
            </h1>
        </div>
        <div class="box radius-top-0">
            <div class="table-container">
                <table class="table is-hoverable is-fullwidth is-size-7">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th><abbr> Name </abbr></th>
                            <th><abbr> Category </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($outOfStockMerchandises as $product)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized"> {{ $product->name ?? 'N/A' }} </td>
                                <td class="is-capitalized"> {{ $product->productCategory->name ?? 'N/A' }} </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
