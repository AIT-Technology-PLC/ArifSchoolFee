@extends('layouts.app')

@section('title')
    Purchase Details
@endsection

@section('content')
    <div class="columns is-marginless">
        <div class="column is-6">
            <div class="box text-green">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column has-text-centered is-paddingless">
                        <span class="icon is-large is-size-1">
                            <i class="fas fa-truck"></i>
                        </span>
                    </div>
                    <div class="column is-paddingless">
                        <div class="is-size-3 has-text-weight-bold">
                            {{ $purchase->shipping_line }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            Shipping Line
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
                            {{ $purchase->calculateTotalPurchasePrice() }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            Total Price ({{ $purchase->company->currency }})
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                Purchase Details
            </h1>
        </div>
        <div class="box radius-bottom-0 mb-0 radius-top-0">
            @if (!$purchase->isAddedToInventory())
                <div class="box has-background-white-ter has-text-left mb-6">
                    <p class="has-text-grey text-purple is-size-7">
                        Product(s) listed below are still not added to your Inventory.
                        <br>
                        Add product(s) automatically by clicking on the button.
                    </p>
                    <button id="openAddToInventoryModal" class="button bg-purple has-text-white mt-5">
                        <span class="icon">
                            <i class="fas fa-plus"></i>
                        </span>
                        <span>
                            Add to Inventory
                        </span>
                    </button>
                    @error('warehouse_id')
                        <span class="help has-text-danger" role="alert">
                            Please assign a warehouse to add the products to.
                        </span>
                    @enderror
                </div>
            @else
                <div class="box has-background-white-ter has-text-left mb-6">
                    <p class="has-text-grey text-green">
                        <span class="icon">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        <span>
                            Product(s) listed below have been to your Inventory.
                        </span>
                    </p>
                </div>
            @endif
            <div class="table-container">
                <table class="table is-hoverable is-fullwidth is-size-7 has-text-centered">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th><abbr> Product </abbr></th>
                            <th><abbr> Supplier </abbr></th>
                            <th><abbr> Quantity </abbr></th>
                            <th><abbr> Unit Price </abbr></th>
                            <th><abbr> Total </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchase->purchaseDetails as $purchaseDetail)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized">
                                    {{ $purchaseDetail->product->name }}
                                </td>
                                <td class="is-capitalized">
                                    {{ $purchaseDetail->supplier->company_name ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ number_format($purchaseDetail->quantity, 2) }}
                                    {{ $purchaseDetail->product->unit_of_measurement }}
                                </td>
                                <td>
                                    {{ $purchase->company->currency }}.
                                    {{ number_format($purchaseDetail->unit_price, 2) }}
                                </td>
                                <td>
                                    {{ $purchase->company->currency }}.
                                    {{ number_format($purchaseDetail->quantity * $purchaseDetail->unit_price, 2) }}
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
                                    <i class="fas fa-money-bill"></i>
                                </span>
                                <span>
                                    Purchase Summary
                                </span>
                            </h1>
                        </div>
                        <div class="mb-4">
                            <h1 class="is-uppercase has-text-weight-light has-text-white">
                                Shipping Company
                            </h1>
                            <h2 class="subtitle has-text-white has-text-weight-medium">
                                {{ $purchase->shipping_line ?? '' }}
                            </h2>
                        </div>
                        <div class="mb-4">
                            <h1 class="is-uppercase has-text-weight-light has-text-white">
                                Status
                            </h1>
                            <h2 class="subtitle has-text-white has-text-weight-medium">
                                {{ $purchase->status }}
                            </h2>
                        </div>
                        <div class="mb-4">
                            <h1 class="is-uppercase has-text-weight-light has-text-white">
                                Shipping Date
                            </h1>
                            <h2 class="subtitle has-text-white has-text-weight-medium">
                                {{ $purchase->shipped_at ? $purchase->shipped_at->toFormattedDateString() : 'Not Shipped' }}
                            </h2>
                        </div>
                        <div class="mb-4">
                            <h1 class="is-uppercase has-text-weight-light has-text-white">
                                Delivery Date
                            </h1>
                            <h2 class="subtitle has-text-white has-text-weight-medium">
                                {{ $purchase->delivered_at ? $purchase->delivered_at->toFormattedDateString() : 'Not Delivered' }}
                            </h2>
                        </div>
                        <div class="mb-4">
                            <h1 class="is-uppercase has-text-weight-light has-text-white">
                                Total Price
                            </h1>
                            <h2 class="subtitle has-text-white has-text-weight-medium">
                                {{ $purchase->company->currency }}.
                                {{ $purchase->calculateTotalPurchasePrice() }}
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('purchases.add-to-inventory-modal')
@endsection
