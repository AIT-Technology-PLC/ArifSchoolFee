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
                    <div class="column is-4 has-text-centered has-text-grey">
                        <a href="{{ route('merchandises.level') }}" class="button is-rounded has-background-white-ter text-green is-size-4">
                            <span class="icon">
                                <i class="fas fa-chart-bar"></i>
                            </span>
                        </a>
                        <br>
                        <span class="is-size-6 is-size-7-mobile">
                            Merchandise Inventory
                        </span>
                    </div>
                    @can('onlyPremiumOrProfessional', auth()->user()->employee->company)
                        @can('Read GDN')
                            <div class="column is-4 has-text-centered has-text-grey">
                                <a href="{{ route('gdns.index') }}" class="button is-rounded has-background-white-ter text-green is-size-4">
                                    <span class="icon">
                                        <i class="fas fa-file-invoice"></i>
                                    </span>
                                </a>
                                <br>
                                <span class="is-size-6 is-size-7-mobile">
                                    DO/GDN
                                </span>
                            </div>
                        @endcan
                        @can('Read GRN')
                            <div class="column is-4 has-text-centered has-text-grey">
                                <a href="{{ route('grns.index') }}" class="button is-rounded has-background-white-ter text-green is-size-4">
                                    <span class="icon">
                                        <i class="fas fa-file-signature"></i>
                                    </span>
                                </a>
                                <br>
                                <span class="is-size-6 is-size-7-mobile">
                                    GRN
                                </span>
                            </div>
                        @endcan
                        @can('Read Transfer')
                            <div class="column is-4 has-text-centered has-text-grey">
                                <a href="{{ route('transfers.index') }}" class="button is-rounded has-background-white-ter text-green is-size-4">
                                    <span class="icon">
                                        <i class="fas fa-exchange-alt   "></i>
                                    </span>
                                </a>
                                <br>
                                <span class="is-size-6 is-size-7-mobile">
                                    Transfers
                                </span>
                            </div>
                        @endcan
                    @endcan
                    @can('Read Sale')
                        <div class="column is-4 has-text-centered has-text-grey">
                            <a href="{{ route('sales.index') }}" class="button is-rounded has-background-white-ter text-green is-size-4">
                                <span class="icon">
                                    <i class="fas fa-tags"></i>
                                </span>
                            </a>
                            <br>
                            <span class="is-size-6 is-size-7-mobile">
                                Sales
                            </span>
                        </div>
                    @endcan
                    @can('onlyPremiumOrProfessional', auth()->user()->employee->company)
                        @can('Read Sale')
                            <div class="column is-4 has-text-centered has-text-grey">
                                <a href="{{ route('customers.index') }}" class="button is-rounded has-background-white-ter text-green is-size-4">
                                    <span class="icon">
                                        <i class="fas fa-user"></i>
                                    </span>
                                </a>
                                <br>
                                <span class="is-size-6 is-size-7-mobile">
                                    Customers
                                </span>
                            </div>
                        @endcan
                    @endcan
                    @can('onlyPremiumOrProfessional', auth()->user()->employee->company)
                        @can('Read Sale')
                            <div class="column is-4 has-text-centered has-text-grey">
                                <a href="{{ route('purchase-orders.index') }}" class="button is-rounded has-background-white-ter text-green is-size-4">
                                    <span class="icon">
                                        <i class="fas fa-file-alt"></i>
                                    </span>
                                </a>
                                <br>
                                <span class="is-size-6 is-size-7-mobile">
                                    Purchase Orders
                                </span>
                            </div>
                        @endcan
                    @endcan
                    @can('Read Purchase')
                        <div class="column is-4 has-text-centered has-text-grey">
                            <a href="{{ route('purchases.index') }}" class="button is-rounded has-background-white-ter text-green is-size-4">
                                <span class="icon">
                                    <i class="fas fa-shopping-bag"></i>
                                </span>
                            </a>
                            <br>
                            <span class="is-size-6 is-size-7-mobile">
                                Purchases
                            </span>
                        </div>
                    @endcan
                    @can('onlyPremiumOrProfessional', auth()->user()->employee->company)
                        @can('Read Purchase')
                            <div class="column is-4 has-text-centered has-text-grey">
                                <a href="{{ route('suppliers.index') }}" class="button is-rounded has-background-white-ter text-green is-size-4">
                                    <span class="icon">
                                        <i class="fas fa-address-card"></i>
                                    </span>
                                </a>
                                <br>
                                <span class="is-size-6 is-size-7-mobile">
                                    Suppliers
                                </span>
                            </div>
                        @endcan
                    @endcan
                    <div class="column is-4 has-text-centered has-text-grey">
                        <a href="{{ route('categories.index') }}" class="button is-rounded has-background-white-ter text-green is-size-4">
                            <span class="icon">
                                <i class="fas fa-layer-group"></i>
                            </span>
                        </a>
                        <br>
                        <span class="is-size-6 is-size-7-mobile">
                            Categories
                        </span>
                    </div>
                    <div class="column is-4 has-text-centered has-text-grey">
                        <a href="{{ route('products.index') }}" class="button is-rounded has-background-white-ter text-green is-size-4">
                            <span class="icon">
                                <i class="fas fa-th"></i>
                            </span>
                        </a>
                        <br>
                        <span class="is-size-6 is-size-7-mobile">
                            Products
                        </span>
                    </div>
                    @can('onlyPremiumOrProfessional', auth()->user()->employee->company)
                        @can('Read Warehouse')
                            <div class="column is-4 has-text-centered has-text-grey">
                                <a href="{{ route('warehouses.index') }}" class="button is-rounded has-background-white-ter text-green is-size-4">
                                    <span class="icon">
                                        <i class="fas fa-warehouse"></i>
                                    </span>
                                </a>
                                <br>
                                <span class="is-size-6 is-size-7-mobile">
                                    Warehouses
                                </span>
                            </div>
                        @endcan
                    @endcan
                    @can('Read Employee')
                        <div class="column is-4 has-text-centered has-text-grey">
                            <a href="{{ route('employees.index') }}" class="button is-rounded has-background-white-ter text-green is-size-4">
                                <span class="icon">
                                    <i class="fas fa-users"></i>
                                </span>
                            </a>
                            <br>
                            <span class="is-size-6 is-size-7-mobile">
                                Employees
                            </span>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
