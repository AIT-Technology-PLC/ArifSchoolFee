@extends('layouts.app')

@section('title')
    General Menu
@endsection

@section('content')
    <div class="columns is-marginless is-centered">
        <div class="column is-10">
            <div class="box radius-bottom-0 mb-0 has-background-white-bis">
                <h1 class="title text-green has-text-weight-medium is-size-5">
                    General Menu
                </h1>
            </div>
            <div class="box radius-top-0">
                <div class="columns is-marginless is-multiline is-mobile">
                    <div class="column is-4 has-text-centered text-green">
                        <a href="{{ route('merchandises.level') }}" class="button is-rounded text-green is-size-4">
                            <span class="icon">
                                <i class="fas fa-chart-bar"></i>
                            </span>
                        </a>
                        <br>
                        <span class="is-size-6 is-size-7-mobile">
                            Merchandise Inventory
                        </span>
                    </div>
                    <div class="column is-4 has-text-centered text-purple">
                        <a href="{{ route('gdns.index') }}" class="button is-rounded text-purple is-size-4">
                            <span class="icon">
                                <i class="fas fa-file-invoice"></i>
                            </span>
                        </a>
                        <br>
                        <span class="is-size-6 is-size-7-mobile">
                            SIV/GDN
                        </span>
                    </div>
                    <div class="column is-4 has-text-centered text-gold">
                        <a href="{{ route('grns.index') }}" class="button is-rounded text-gold is-size-4">
                            <span class="icon">
                                <i class="fas fa-file-signature"></i>
                            </span>
                        </a>
                        <br>
                        <span class="is-size-6 is-size-7-mobile">
                            GRN
                        </span>
                    </div>
                    <div class="column is-4 has-text-centered text-blue">
                        <a href="{{ route('transfers.index') }}" class="button is-rounded text-blue is-size-4">
                            <span class="icon">
                                <i class="fas fa-exchange-alt   "></i>
                            </span>
                        </a>
                        <br>
                        <span class="is-size-6 is-size-7-mobile">
                            Transfers
                        </span>
                    </div>
                    <div class="column is-4 has-text-centered text-green">
                        <a href="{{ route('sales.index') }}" class="button is-rounded text-green is-size-4">
                            <span class="icon">
                                <i class="fas fa-tags"></i>
                            </span>
                        </a>
                        <br>
                        <span class="is-size-6 is-size-7-mobile">
                            Sales
                        </span>
                    </div>
                    <div class="column is-4 has-text-centered text-purple">
                        <a href="{{ route('purchase-orders.index') }}" class="button is-rounded text-purple is-size-4">
                            <span class="icon">
                                <i class="fas fa-file-alt"></i>
                            </span>
                        </a>
                        <br>
                        <span class="is-size-6 is-size-7-mobile">
                            Purchase Orders
                        </span>
                    </div>
                    <div class="column is-4 has-text-centered text-gold">
                        <a href="{{ route('purchases.index') }}" class="button is-rounded text-gold is-size-4">
                            <span class="icon">
                                <i class="fas fa-shopping-bag"></i>
                            </span>
                        </a>
                        <br>
                        <span class="is-size-6 is-size-7-mobile">
                            Purchases
                        </span>
                    </div>
                    <div class="column is-4 has-text-centered text-blue">
                        <a href="{{ route('suppliers.index') }}" class="button is-rounded text-blue is-size-4">
                            <span class="icon">
                                <i class="fas fa-boxes"></i>
                            </span>
                        </a>
                        <br>
                        <span class="is-size-6 is-size-7-mobile">
                            Suppliers
                        </span>
                    </div>
                    <div class="column is-4 has-text-centered text-green">
                        <a href="{{ route('categories.index') }}" class="button is-rounded text-green is-size-4">
                            <span class="icon">
                                <i class="fas fa-boxes"></i>
                            </span>
                        </a>
                        <br>
                        <span class="is-size-6 is-size-7-mobile">
                            Categories
                        </span>
                    </div>
                    <div class="column is-4 has-text-centered text-purple">
                        <a href="{{ route('products.index') }}" class="button is-rounded text-purple is-size-4">
                            <span class="icon">
                                <i class="fas fa-boxes"></i>
                            </span>
                        </a>
                        <br>
                        <span class="is-size-6 is-size-7-mobile">
                            Products
                        </span>
                    </div>
                    <div class="column is-4 has-text-centered text-gold">
                        <a href="{{ route('warehouses.index') }}" class="button is-rounded text-gold is-size-4">
                            <span class="icon">
                                <i class="fas fa-boxes"></i>
                            </span>
                        </a>
                        <br>
                        <span class="is-size-6 is-size-7-mobile">
                            Warehouses
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
