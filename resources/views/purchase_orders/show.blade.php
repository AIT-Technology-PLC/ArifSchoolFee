@extends('layouts.app')

@section('title')
    Purchase Order Details
@endsection

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column">
            <div class="box text-green">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column has-text-centered is-paddingless">
                        <span class="icon is-large is-size-1">
                            <i class="fas fa-file-alt"></i>
                        </span>
                    </div>
                    <div class="column is-paddingless">
                        <div class="is-size-3 has-text-weight-bold">
                            {{ $purchaseOrder->code ?? 'N/A' }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            Purchase Order No
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="box text-green">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column has-text-centered is-paddingless">
                        <span class="icon is-large is-size-1">
                            <i class="fas fa-calendar-day"></i>
                        </span>
                    </div>
                    <div class="column is-paddingless">
                        <div class="is-size-3 is-size-5-mobile has-text-weight-bold">
                            {{ $purchaseOrder->received_on->toFormattedDateString() }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            Received On
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="column is-12">
            <div class="box text-green">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column has-text-centered is-paddingless">
                        <span class="icon is-large is-size-1">
                            <i class="fas fa-user"></i>
                        </span>
                    </div>
                    <div class="column is-paddingless">
                        <div class="is-size-4 is-size-7-mobile has-text-weight-bold">
                            {{ $purchaseOrder->customer->company_name ?? 'N/A' }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            Customer
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
                                Purchase Order Details
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-item is-justify-content-left">
                        <div>
                            <a href="{{ route('purchase-orders.edit', $purchaseOrder->id) }}" class="button is-small bg-green has-text-white">
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
            @if (!$purchaseOrder->isPurchaseOrderClosed())
                <div class="box has-background-white-ter has-text-left mb-6">
                    <p class="has-text-grey text-purple is-size-7">
                        This PO is still not closed. <br> Click the button below to close this PO.
                    </p>
                    <form id="formOne" action="{{ route('purchase-orders.close', $purchaseOrder->id) }}" method="post" novalidate>
                        @csrf
                        <button id="closePurchaseOrderButton" class="button bg-purple has-text-white mt-5 is-size-7-mobile">
                            <span class="icon">
                                <i class="fas fa-file-alt"></i>
                            </span>
                            <span>
                                Close PO
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
                            This PO is closed.
                        </span>
                    </p>
                </div>
            @endif
            <div class="table-container">
                <table class="table is-hoverable is-fullwidth is-size-7">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th><abbr> Product </abbr></th>
                            <th><abbr> Total Quantity </abbr></th>
                            <th><abbr> Quantity Remaining  </abbr></th>
                            <th><abbr> Unit Price </abbr></th>
                            <th><abbr> Total </abbr></th>
                            <th><abbr> Description </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchaseOrder->purchaseOrderDetails as $purchaseOrderDetail)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized">
                                    {{ $purchaseOrderDetail->product->name }}
                                </td>
                                <td class="is-capitalized">
                                    {{ number_format($purchaseOrderDetail->quantity, 2) }}
                                    {{ $purchaseOrderDetail->product->unit_of_measurement }}
                                </td>
                                <td class="is-capitalized">
                                    {{ number_format($purchaseOrderDetail->quantity_left, 2) }}
                                    {{ $purchaseOrderDetail->product->unit_of_measurement }}
                                </td>
                                <td>
                                    {{ $purchaseOrder->company->currency }}.
                                    {{ number_format($purchaseOrderDetail->unit_price, 2) }}
                                </td>
                                <td>
                                    {{ $purchaseOrder->company->currency }}.
                                    {{ number_format($purchaseOrderDetail->quantity * $purchaseOrderDetail->unit_price, 2) }}
                                </td>
                                <td>
                                    {!! nl2br(e($purchaseOrderDetail->description)) !!}
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
                                    <i class="fas fa-file-alt"></i>
                                </span>
                                <span>
                                    PO Summary
                                </span>
                            </h1>
                        </div>
                        <div class="mb-4">
                            <h1 class="is-uppercase has-text-weight-light has-text-white">
                                PO No
                            </h1>
                            <h2 class="subtitle has-text-white has-text-weight-medium">
                                {{ $purchaseOrder->code ?? 'N/A' }}
                            </h2>
                        </div>
                        <div class="mb-4">
                            <h1 class="is-uppercase has-text-weight-light has-text-white">
                                Customer
                            </h1>
                            <h2 class="subtitle has-text-white has-text-weight-medium">
                                {{ $purchaseOrder->customer->company_name ?? 'N/A' }}
                            </h2>
                        </div>
                        @if (!$purchaseOrder->isPurchaseOrderClosed())
                            <div class="mb-4">
                                <h1 class="is-uppercase has-text-weight-light has-text-white">
                                    Status
                                </h1>
                                <h2 class="subtitle has-text-white has-text-weight-medium">
                                    Still Open
                                </h2>
                            </div>
                        @endif
                        <div class="mb-4">
                            <h1 class="is-uppercase has-text-weight-light has-text-white">
                                Received On
                            </h1>
                            <h2 class="subtitle has-text-white has-text-weight-medium">
                                {{ $purchaseOrder->received_on ? $purchaseOrder->received_on->toFormattedDateString() : 'N/A' }}
                            </h2>
                        </div>
                        <div class="mb-4">
                            <h1 class="is-uppercase has-text-weight-light has-text-white">
                                Total Price
                            </h1>
                            <h2 class="subtitle has-text-white has-text-weight-medium">
                                {{ $purchaseOrder->company->currency }}.
                                {{ $purchaseOrder->totalPurchaseOrderPrice }}
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
