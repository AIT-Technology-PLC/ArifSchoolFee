@extends('layouts.app')

@section('title')
    Customers Management
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
                            {{ $totalCustomers }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            Total Customers
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
                            Add new customer and assign products to them
                        </div>
                        <div class="is-size-3">
                            <a href="{{ route('customers.create') }}" class="button bg-purple has-text-white has-text-weight-medium is-size-7 px-5 py-4 mt-3">
                                <span class="icon">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span>
                                    Create New Customer
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
                Customer Account Management
            </h1>
        </div>
        <div class="box radius-top-0">
            <div class="table-container">
                <table class="table is-hoverable is-fullwidth is-size-7">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th class="text-purple"><abbr> Company </abbr></th>
                            <th><abbr> Contact </abbr></th>
                            <th><abbr> Email </abbr></th>
                            <th><abbr> Phone </abbr></th>
                            <th><abbr> Country </abbr></th>
                            <th class="has-text-right"><abbr> Added On </abbr></th>
                            @can('delete', $customers->first())
                                <th><abbr> Added By </abbr></th>
                                <th><abbr> Edited By </abbr></th>
                            @endcan
                            <th><abbr> Actions </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized">
                                    <span class="tag is-small bg-purple has-text-white">
                                        {{ $customer->company_name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="is-capitalized">{{ $customer->contact_name ?? 'N/A' }}</td>
                                <td>{{ $customer->email ?? 'N/A' }}</td>
                                <td class="is-capitalized">{{ $customer->phone ?? 'N/A' }}</td>
                                <td class="is-capitalized">{{ $customer->country ?? 'N/A' }}</td>
                                <td class="has-text-right"> {{ $customer->created_at->toFormattedDateString() }} </td>
                                @can('delete', $customer)
                                    <td> {{ $customer->createdBy->name ?? 'N/A' }} </td>
                                    <td> {{ $customer->updatedBy->name ?? 'N/A' }} </td>
                                @endcan
                                <td>
                                    <a href="{{ route('customers.edit', $customer->id) }}" data-title="Modify Customer Data">
                                        <span class="tag is-white btn-green is-outlined is-small text-green has-text-weight-medium">
                                            <span class="icon">
                                                <i class="fas fa-pen-square"></i>
                                            </span>
                                            <span>
                                                Edit
                                            </span>
                                        </span>
                                    </a>
                                    @include('components.delete_button', ['model' => 'customers',
                                    'id' => $customer->id])
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
