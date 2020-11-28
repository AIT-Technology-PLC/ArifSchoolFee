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
                            Total Price
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
            <div class="columns is-marginless">
                <div class="column has-text-weight-bold text-green">
                    #
                </div>
                <div class="column has-text-weight-bold text-green">
                    Product
                </div>
                <div class="column has-text-weight-bold text-green">
                    Quantity
                </div>
                <div class="column has-text-weight-bold text-green">
                    Unit Price
                </div>
                <div class="column has-text-weight-bold text-green">
                    Total
                </div>
            </div>
            @foreach ($purchase->purchaseDetails as $purchaseDetail)
                <div class="columns is-marginless">
                    <div class="column">
                        {{ $loop->index + 1 }}
                    </div>
                    <div class="column">
                        {{ $purchaseDetail->product->name }}
                    </div>
                    <div class="column">
                        {{ $purchaseDetail->quantity }}
                        {{ $purchaseDetail->product->unit_of_measurement }}
                    </div>
                    <div class="column">
                        {{ $purchaseDetail->unit_price }}
                    </div>
                    <div class="column">
                        {{ $purchase->company->currency }}.
                        {{ $purchaseDetail->quantity * $purchaseDetail->unit_price }}
                    </div>
                </div>
            @endforeach
            <div class="columns is-marginless">
                <div class="column is-one-fifth is-offset-four-fifths">
                    <p class="has-text-weight-bold">
                        {{ $purchase->company->currency }}.
                        {{ $purchase->calculateTotalPurchasePrice() }}
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
