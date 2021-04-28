@extends('layouts.app')

@section('title')
    Purchase Order Details
@endsection

@section('content')
    <div class="box mt-3 mx-3 m-lr-0">
        <div class="columns is-marginless is-multiline">
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-file-alt"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $purchaseOrder->code ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Purchase Order No
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
                                <i class="fas fa-spinner"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $purchaseOrder->isPurchaseOrderClosed() ? 'PO Closed' : 'Still Opened' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Status
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
                                {{ $purchaseOrder->customer->company_name ?? 'N/A' }}
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
                                {{ $purchaseOrder->received_on->toFormattedDateString() ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Received On
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
                                {{ $purchaseOrder->totalPurchaseOrderPrice }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Total Price ({{ $purchaseOrder->company->currency }})
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
                                {{ $purchaseOrder->totalPurchaseOrderPriceWithVAT }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Total Price with VAT ({{ $purchaseOrder->company->currency }})
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
                                {!! is_null($purchaseOrder->description) ? 'N/A' : nl2br(e($purchaseOrder->description)) !!}
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
                <div class="box is-shadowless has-background-white-ter has-text-left mb-6">
                    <p class="has-text-grey text-green is-size-6">
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
                            <th><abbr> Quantity Remaining </abbr></th>
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
        </div>
    </section>
@endsection
