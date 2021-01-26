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
                            @if ($sale->isSaleManual())
                                <a href="{{ route('gdns.create') }}" class="button is-small bg-purple has-text-white">
                                    <span class="icon">
                                        <i class="fas fa-plus-circle"></i>
                                    </span>
                                    <span>
                                        New SIV/GDN
                                    </span>
                                </a>
                            @endif
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
            @if (!$sale->isSaleManual())
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
                    <div class="message is-success">
                        <p class="message-body">
                            <span class="icon">
                                <i class="fas fa-check-circle"></i>
                            </span>
                            <span>
                                Sale has been closed and products are subtracted from inventory.
                            </span>
                        </p>
                    </div>
                @endif
            @endif
            <div class="table-container">
                <table class="table is-hoverable is-fullwidth is-size-7 has-text-centered">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            @if (!$sale->isSaleManual())
                                <th><abbr> Warehouse </abbr></th>
                            @endif
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
                                @if (!$sale->isSaleManual())
                                    <td class="is-capitalized">
                                        {{ $saleDetail->warehouse->name ?? 'N/A' }}
                                    </td>
                                @endif
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
            @if ($sale->isSaleManual())
                <div class="box has-background-white-bis">
                    <h1 class="title is-size-5 text-green has-text-centered">
                        SIV/GDN for this sale
                    </h1>
                    <div class="table-container has-background-white-bis">
                        <table class="table is-hoverable is-fullwidth is-size-7 has-background-white-bis">
                            <thead>
                                <tr>
                                    <th><abbr> # </abbr></th>
                                    <th><abbr> SIV/GDN No </abbr></th>
                                    <th><abbr> Status </abbr></th>
                                    <th><abbr> Issued on </abbr></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sale->gdns as $gdn)
                                    <tr>
                                        <td> {{ $loop->index + 1 }} </td>
                                        <td class="is-capitalized">
                                            <a class="is-underlined" href="{{ route('gdns.show', $gdn->id) }}">
                                                {{ $gdn->code }}
                                            </a>
                                        </td>
                                        <td>
                                            @if ($gdn->isGdnSubtracted())
                                                <span class="tag is-small bg-purple has-text-white">
                                                    {{ $gdn->status ?? 'N/A' }}
                                                </span>
                                            @else
                                                <span class="tag is-small bg-blue has-text-white">
                                                    {{ $gdn->status ?? 'N/A' }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="is-capitalized">
                                            {{ $gdn->issued_on->toFormattedDateString() }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
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
                                Payment Method
                            </h1>
                            <h2 class="subtitle has-text-white has-text-weight-medium">
                                {{ $sale->payment_type }}
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
                        @if (!$sale->isSaleManual())
                            <div class="mb-4">
                                <h1 class="is-uppercase has-text-weight-light has-text-white">
                                    Status
                                </h1>
                                <h2 class="subtitle has-text-white has-text-weight-medium">
                                    {{ $sale->status }}
                                </h2>
                            </div>
                        @endif
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
