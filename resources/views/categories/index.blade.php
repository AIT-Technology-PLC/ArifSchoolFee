@extends('layouts.app')

@section('title')
    Product Categories
@endsection

@section('content')
    <div class="columns is-marginless">
        <div class="column is-6">
            <div class="box text-green">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column has-text-centered is-paddingless">
                        <span class="icon is-large is-size-1">
                            <i class="fas fa-layer-group"></i>
                        </span>
                    </div>
                    <div class="column is-paddingless">
                        <div class="is-size-3 has-text-weight-bold">
                            {{ $totalProductCategoriesOfCompany }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            Total Product Categories
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
                            Do you want to assign your products to new product category?
                        </div>
                        <div class="is-size-3">
                            <a href="{{ route('categories.create') }}" class="button bg-purple has-text-white has-text-weight-medium is-size-7 px-5 py-4 mt-3">
                                <span class="icon">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span>
                                    Create New Category
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
                Product Categories List
            </h1>
        </div>
        <div class="box radius-top-0">
            <div class="table-container">
                <table class="table is-hoverable is-fullwidth is-size-7">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th><abbr> Category </abbr></th>
                            <th><abbr> Properties </abbr></th>
                            <th class="has-text-centered text-purple"><abbr> Products </abbr></th>
                            <th><abbr> Description </abbr></th>
                            <th class="has-text-right"><abbr> Created On </abbr></th>
                            @can('delete', $categories->first())
                                <th><abbr> Added By </abbr></th>
                                <th><abbr> Edited By </abbr></th>
                            @endcan
                            <th class="has-text-centered"><abbr> Actions </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized"> {{ $category->name }} </td>
                                <td class="is-capitalized">
                                    @if (is_null($category->properties))
                                        {{ 'N/A' }}
                                    @else
                                        @foreach ($category->properties as $property)
                                            <b>{{ $property['key'] }}</b>: {{ $property['value'] }}<br />
                                        @endforeach
                                    @endif
                                </td>
                                <td class="has-text-centered text-purple has-text-weight-bold"> {{ $category->products->count() }} </td>
                                <td> {{ substr($category->description, 0, 40) ?? 'N/A' }} </td>
                                <td class="has-text-right"> {{ $category->created_at->toFormattedDateString() }} </td>
                                @can('delete', $category)
                                    <td> {{ $category->createdBy->name ?? 'N/A' }} </td>
                                    <td> {{ $category->updatedBy->name ?? 'N/A' }} </td>
                                @endcan
                                <td class="has-text-centered">
                                    <a href="{{ route('categories.edit', $category->id) }}" data-title="Modify Category Data">
                                        <span class="tag is-white btn-green is-outlined is-small text-green has-text-weight-medium">
                                            <span class="icon">
                                                <i class="fas fa-pen-square"></i>
                                            </span>
                                            <span>
                                                Edit
                                            </span>
                                        </span>
                                    </a>
                                    @include('components.delete_button', ['model' => 'categories',
                                    'id' => $category->id])
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
