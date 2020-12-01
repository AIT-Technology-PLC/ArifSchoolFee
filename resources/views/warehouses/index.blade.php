@extends('layouts.app')

@section('title')
    Warehouses
@endsection

@section('content')
    <div class="columns is-marginless">
        <div class="column is-6">
            <div class="box text-green">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column has-text-centered is-paddingless">
                        <span class="icon is-large is-size-1">
                            <i class="fas fa-warehouse"></i>
                        </span>
                    </div>
                    <div class="column is-paddingless">
                        <div class="is-size-3 has-text-weight-bold">
                            {{ $totalWarehousesOfCompany }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            Total Warehouses
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
                            Do you want to new warehouse where your inventory will be stored?
                        </div>
                        <div class="is-size-3">
                            <a href="{{ route('warehouses.create') }}" class="button bg-purple has-text-white has-text-weight-medium is-size-7 px-5 py-4 mt-3">
                                <span class="icon">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span>
                                    Create New Warehouse
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
                Warehouse List
            </h1>
        </div>
        <div class="box radius-top-0">
            <div class="table-container">
                <table class="table is-hoverable is-fullwidth is-size-7">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th><abbr> Warehouse Name </abbr></th>
                            <th><abbr> Location </abbr></th>
                            <th><abbr> Created On </abbr></th>
                            <th><abbr> Description </abbr></th>
                            @can('delete', $warehouses->first())
                                <th><abbr> Added By </abbr></th>
                                <th><abbr> Edited By </abbr></th>
                            @endcan
                            <th><abbr> Actions </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($warehouses as $warehouse)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized"> {{ $warehouse->name }} </td>
                                <td class="is-capitalized"> {{ $warehouse->location ?? 'N/A' }} </td>
                                <td class="is-capitalized"> {{ $warehouse->created_at->toDayDateTimeString() }} </td>
                                <td> {{ substr($warehouse->description, 0, 40) ?? 'N/A' }} </td>
                                @can('delete', $warehouse)
                                    <td> {{ $warehouse->createdBy->name ?? 'N/A' }} </td>
                                    <td> {{ $warehouse->updatedBy->name ?? 'N/A' }} </td>
                                @endcan
                                <td>
                                    <a href="{{ route('warehouses.edit', $warehouse->id) }}" title="Modify Warehouse Data" class="text-green is-size-6">
                                        <span class="icon">
                                            <i class="fas fa-pen-square"></i>
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
