@extends('layouts.app')

@section('title')
    Suppliers Management
@endsection

@section('content')
    <div class="columns is-marginless">
        <div class="column is-6">
            <div class="box text-green">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column has-text-centered is-paddingless">
                        <span class="icon is-large is-size-1">
                            <i class="fas fa-address-card"></i>
                        </span>
                    </div>
                    <div class="column is-paddingless">
                        <div class="is-size-3 has-text-weight-bold">
                            {{ $totalSuppliers }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            Total Suppliers
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
                            Add new supplier and assign products to them
                        </div>
                        <div class="is-size-3">
                            <a href="{{ route('suppliers.create') }}" class="button bg-purple has-text-white has-text-weight-medium is-size-7 px-5 py-4 mt-3">
                                <span class="icon">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span>
                                    Create New Supplier
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
                Supplier Account Management
            </h1>
        </div>
        <div class="box radius-top-0">
            @include('components.deleted_message', ['model' => 'Supplier'])
            <table id="table_id" class="table-container table is-hoverable is-fullwidth is-size-7">
                <thead>
                    <tr>
                        <th><abbr> # </abbr></th>
                        <th class="text-purple"><abbr> Company </abbr></th>
                        <th><abbr> Contact </abbr></th>
                        <th><abbr> Email </abbr></th>
                        <th><abbr> Phone </abbr></th>
                        <th><abbr> Country </abbr></th>
                        <th class="has-text-right"><abbr> Added On </abbr></th>
                        @can('delete', $suppliers->first())
                            <th><abbr> Added By </abbr></th>
                            <th><abbr> Edited By </abbr></th>
                        @endcan
                        <th><abbr> Actions </abbr></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($suppliers as $supplier)
                        <tr>
                            <td> {{ $loop->index + 1 }} </td>
                            <td class="is-capitalized">
                                <span class="tag is-small bg-purple has-text-white">
                                    {{ $supplier->company_name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="is-capitalized">{{ $supplier->contact_name ?? 'N/A' }}</td>
                            <td>{{ $supplier->email ?? 'N/A' }}</td>
                            <td class="is-capitalized">{{ $supplier->phone ?? 'N/A' }}</td>
                            <td class="is-capitalized">{{ $supplier->country ?? 'N/A' }}</td>
                            <td class="has-text-right"> {{ $supplier->created_at->toFormattedDateString() }} </td>
                            @can('delete', $supplier)
                                <td> {{ $supplier->createdBy->name ?? 'N/A' }} </td>
                                <td> {{ $supplier->updatedBy->name ?? 'N/A' }} </td>
                            @endcan
                            <td>
                                <a href="{{ route('suppliers.edit', $supplier->id) }}" data-title="Modify Supplier Data">
                                    <span class="tag is-white btn-green is-outlined is-small text-green has-text-weight-medium">
                                        <span class="icon">
                                            <i class="fas fa-pen-square"></i>
                                        </span>
                                        <span>
                                            Edit
                                        </span>
                                    </span>
                                </a>
                                @include('components.delete_button', ['model' => 'suppliers',
                                'id' => $supplier->id])
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
