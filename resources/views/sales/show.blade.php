@extends('layouts.app')

@section('title')
    Sale Details
@endsection

@section('content')
    <div class="box mt-3 mx-3 m-lr-0">
        <div class="columns is-marginless is-multiline">
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-hashtag"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
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
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-credit-card"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $sale->payment_type ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Payment Type
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-user"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $sale->customer->company_name ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Customer
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-calendar-day"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $sale->sold_on->toFormattedDateString() ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Sold On
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-dollar-sign"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $sale->totalSalePrice }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Total Price ({{ $sale->company->currency }})
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-purple">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-dollar-sign"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $sale->totalSalePriceWithVAT }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Total Price with VAT ({{ $sale->company->currency }})
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <div>
                    <div class="columns is-marginless is-vcentered text-green">
                        <div class="column">
                            <div class="has-text-weight-bold">
                                Details
                            </div>
                            <div class="is-size-7 mt-3">
                                {!! is_null($sale->description) ? 'N/A' : nl2br(e($sale->description)) !!}
                            </div>
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
                                        New DO/GDN
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
                        <form id="formOne" action="{{ route('merchandises.subtractFromInventory', $sale->id . '?model=sales') }}" method="post" novalidate>
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
                        DO/GDN for this sale
                    </h1>
                    <div class="table-container has-background-white-bis">
                        <table class="table is-hoverable is-fullwidth is-size-7 has-background-white-bis">
                            <thead>
                                <tr>
                                    <th><abbr> # </abbr></th>
                                    <th><abbr> DO/GDN No </abbr></th>
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
        </div>
    </section>
@endsection
