@extends('layouts.app')

@section('title')
    Product Management
@endsection

@section('content')
    <div class="columns is-marginless">
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
    </div>
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                Products List
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
                            <th><abbr> Type </abbr></th>
                            <th><abbr> Selling Price </abbr></th>
                            <th><abbr> Purchase Price </abbr></th>
                            <th><abbr> PAR Level</abbr></th>
                            <th><abbr> Properties </abbr></th>
                            <th><abbr> Description </abbr></th>
                            <th><abbr> Actions </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized"> {{ $product->name ?? 'N/A' }} </td>
                                <td class="is-capitalized"> {{ $product->productCategory->name ?? 'N/A' }} </td>
                                <td class="is-capitalized"> {{ $product->type ?? 'N/A' }} </td>
                                <td class="is-capitalized"> {{ $product->selling_price ?? 'N/A' }} </td>
                                <td class="is-capitalized"> {{ $product->purchase_price ?? 'N/A' }} </td>
                                <td class="is-capitalized"> {{ $product->min_on_hand }} {{ $product->unit_of_measurement ?? 'N/A' }} </td>
                                <td class="is-capitalized">
                                    @if (is_null($product->properties))
                                        {{ 'N/A' }}
                                    @else
                                        @foreach ($product->properties as $property)
                                            <b>{{ $property['key'] }}</b>: {{ $property['value'] }}<br />
                                        @endforeach
                                    @endif
                                </td>
                                <td> {{ substr($product->description, 0, 40) ?? 'N/A' }} </td>
                                <td>
                                    <a href="{{ route('products.edit', $product->id) }}" title="Modify Category Data" class="text-green is-size-6-5">
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
