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
            <x-success-message :message="session('deleted')" />
            <div>
                <table class="regular-datatable is-hoverable is-size-7 display nowrap" data-date="[4]" data-numeric="[]">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th class="text-gold"><abbr> Receipt No </abbr></th>
                            <th class="text-purple"><abbr> Payment Method </abbr></th>
                            <th class="has-text-right text-green"><abbr> Total Price </abbr></th>
                            <th class="has-text-right"><abbr> Sold on </abbr></th>
                            <th><abbr> Prepared By </abbr></th>
                            <th><abbr> Edited By </abbr></th>
                            <th><abbr> Actions </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sales as $sale)
                            <tr class="showRowDetails is-clickable" data-id="{{ route('sales.show', $sale->id) }}">
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized">
                                    <span class="tag bg-gold has-text-white">
                                        {{ $sale->code ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="is-capitalized">
                                    <span class="tag bg-purple has-text-white">
                                        {{ $sale->payment_type ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="has-text-right">
                                    <span class="tag is-small bg-green has-text-white">
                                        {{ userCompany()->currency }}.
                                        @if (userCompany()->isDiscountBeforeVAT())
                                            {{ number_format($sale->grandTotalPrice, 2) }}
                                        @else
                                            {{ number_format($sale->grandTotalPriceAfterDiscount, 2) }}
                                        @endif
                                    </span>
                                </td>
                                <td class="has-text-right">
                                    {{ $sale->sold_on->toFormattedDateString() }}
                                </td>
                                <td> {{ $sale->createdBy->name ?? 'N/A' }} </td>
                                <td> {{ $sale->updatedBy->name ?? 'N/A' }} </td>
                                <td class="actions">
                                    <a href="{{ route('sales.show', $sale->id) }}" data-title="View Details">
                                        <span class="tag is-white btn-purple is-outlined is-small text-green has-text-weight-medium">
                                            <span class="icon">
                                                <i class="fas fa-info-circle"></i>
                                            </span>
                                            <span>
                                                Details
                                            </span>
                                        </span>
                                    </a>
                                    <a href="{{ route('sales.edit', $sale->id) }}" data-title="Modify Sales Data">
                                        <span class="tag is-white btn-green is-outlined is-small text-green has-text-weight-medium">
                                            <span class="icon">
                                                <i class="fas fa-pen-square"></i>
                                            </span>
                                            <span>
                                                Edit
                                            </span>
                                        </span>
                                    </a>
                                    <x-delete-button model="sales" :id="$sale->id" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
