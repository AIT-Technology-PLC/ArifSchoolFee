@extends('layouts.app')

@section('title')
    Sale Details
@endsection

@section('content')
    <div class="columns is-marginless">
        <div class="column is-6">
            <div class="box text-green">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column has-text-centered is-paddingless">
                        <span class="icon is-large is-size-1">
                            <i class="fas fa-hashtag"></i>
                        </span>
                    </div>
                    <div class="column is-paddingless">
                        <div class="is-size-3 has-text-weight-bold">
                            {{ $sale->receipt_no ?? 'N/A' }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            Receipt No
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="column is-6">
            <div class="box text-purple">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column has-text-centered is-paddingless">
                        <span class="icon is-large is-size-1">
                            <i class="fas fa-dollar-sign"></i>
                        </span>
                    </div>
                    <div class="column is-paddingless">
                        <div class="is-size-3 has-text-weight-bold">
                            {{ $sale->totalSalePrice }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            Total Price ({{ $sale->company->currency }})
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <div class="level">
                <div class="level-left">
                    <div class="level-item is-justify-content-left">
                        <div>
                            <h1 class="title text-green has-text-weight-medium is-size-5">
                                Sale Details
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-item is-justify-content-left">
                        <div>
                            <a href="{{ route('sivs.create', ['sale' => $sale->id]) }}" class="button is-small bg-blue has-text-white">
                                <span class="icon">
                                    <i class="fas fa-file"></i>
                                </span>
                                <span>
                                    Create SIV
                                </span>
                            </a>
                            <a href="{{ route('sales.sivs', $sale->id) }}" class="button is-small bg-gold has-text-white">
                                <span class="icon">
                                    <i class="fas fa-file"></i>
                                </span>
                                <span>
                                    Show SIVs
                                </span>
                            </a>
                            <a href="{{ route('sales.edit', $sale->id) }}" class="button is-small bg-green has-text-white">
                                <span class="icon">
                                    <i class="fas fa-pen"></i>
                                </span>
                                <span>
                                    Edit
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box radius-bottom-0 mb-0 radius-top-0">
            <div class="notification bg-gold has-text-white has-text-weight-medium {{ session('message') ? '' : 'is-hidden' }}">
                <span class="icon">
                    <i class="fas fa-times-circle"></i>
                </span>
                <span>
                    {{ session('message') }}
                </span>
            </div>
            @if (!$sale->isSaleSubtracted())
                <div class="box has-background-white-ter has-text-left mb-6">
                    <p class="has-text-grey text-purple is-size-7">
                        Product(s) listed below are still not subtracted from your inventory.
                        <br>
                        Click on the button below to close sale and subtract product(s) from the inventory.
                    </p>
                    <form id="formOne" action="{{ route('merchandises.subtractFromInventory', $sale->id) }}" method="post" novalidate>
                        @csrf
                        <button id="openCloseSaleModal" class="button bg-purple has-text-white mt-5 is-size-7-mobile">
                            <span class="icon">
                                <i class="fas fa-handshake"></i>
                            </span>
                            <span>
                                Close sale & Subtract from inventory
                            </span>
                        </button>
                    </form>
                </div>
            @else
                <div class="box has-background-white-ter has-text-left mb-6">
                    <p class="has-text-grey text-green">
                        <span class="icon">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        <span>
                            Sale has been closed and products are subtracted from inventory.
                        </span>
                    </p>
                </div>
            @endif
            <div class="table-container">
                <table class="table is-hoverable is-fullwidth is-size-7 has-text-centered">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th><abbr> Warehouse </abbr></th>
                            <th><abbr> Product </abbr></th>
                            <th><abbr> Quantity </abbr></th>
                            <th><abbr> Unit Price </abbr></th>
                            <th><abbr> Total </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sale->saleDetails as $saleDetail)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized">
                                    {{ $saleDetail->warehouse->name ?? 'N/A' }}
                                </td>
                                <td class="is-capitalized">
                                    {{ $saleDetail->product->name }}
                                </td>
                                <td>
                                    {{ number_format($saleDetail->quantity, 2) }}
                                    {{ $saleDetail->product->unit_of_measurement }}
                                </td>
                                <td>
                                    {{ $sale->company->currency }}.
                                    {{ number_format($saleDetail->unit_price, 2) }}
                                </td>
                                <td>
                                    {{ $sale->company->currency }}.
                                    {{ number_format($saleDetail->quantity * $saleDetail->unit_price, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="columns is-marginless">
                <div class="column is-4 is-offset-8 px-0">
                    <div class="box bg-green">
                        <div class="mb-5 has-text-centered">
                            <h1 class="is-uppercase has-text-weight-bold has-text-white is-family-monospace">
                                <span class="icon">
                                    <i class="fas fa-tags"></i>
                                </span>
                                <span>
                                    Sale Summary
                                </span>
                            </h1>
                        </div>
                        <div class="mb-4">
                            <h1 class="is-uppercase has-text-weight-light has-text-white">
                                Receipt No
                            </h1>
                            <h2 class="subtitle has-text-white has-text-weight-medium">
                                {{ $sale->receipt_no ?? 'N/A' }}
                            </h2>
                        </div>
                        <div class="mb-4">
                            <h1 class="is-uppercase has-text-weight-light has-text-white">
                                Customer
                            </h1>
                            <h2 class="subtitle has-text-white has-text-weight-medium">
                                {{ $sale->customer->company_name ?? 'N/A' }}
                            </h2>
                        </div>
                        <div class="mb-4">
                            <h1 class="is-uppercase has-text-weight-light has-text-white">
                                Status
                            </h1>
                            <h2 class="subtitle has-text-white has-text-weight-medium">
                                {{ $sale->status }}
                            </h2>
                        </div>
                        <div class="mb-4">
                            <h1 class="is-uppercase has-text-weight-light has-text-white">
                                Sold On
                            </h1>
                            <h2 class="subtitle has-text-white has-text-weight-medium">
                                {{ $sale->sold_on ? $sale->sold_on->toFormattedDateString() : 'N/A' }}
                            </h2>
                        </div>
                        <div class="mb-4">
                            <h1 class="is-uppercase has-text-weight-light has-text-white">
                                Shipping Company
                            </h1>
                            <h2 class="subtitle has-text-white has-text-weight-medium">
                                {{ $sale->shipping_line ?? 'N/A' }}
                            </h2>
                        </div>
                        <div class="mb-4">
                            <h1 class="is-uppercase has-text-weight-light has-text-white">
                                Shipping Date
                            </h1>
                            <h2 class="subtitle has-text-white has-text-weight-medium">
                                {{ $sale->shipped_at ? $sale->shipped_at->toFormattedDateString() : 'N/A' }}
                            </h2>
                        </div>
                        <div class="mb-4">
                            <h1 class="is-uppercase has-text-weight-light has-text-white">
                                Delivery Date
                            </h1>
                            <h2 class="subtitle has-text-white has-text-weight-medium">
                                {{ $sale->delivered_at ? $sale->delivered_at->toFormattedDateString() : 'N/A' }}
                            </h2>
                        </div>
                        <div class="mb-4">
                            <h1 class="is-uppercase has-text-weight-light has-text-white">
                                Total Price
                            </h1>
                            <h2 class="subtitle has-text-white has-text-weight-medium">
                                {{ $sale->company->currency }}.
                                {{ $sale->totalSalePrice }}
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
