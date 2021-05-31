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

                    @can('Read Merchandise')
                        <div class="column is-4 has-text-centered has-text-grey">
                            <a href="{{ route('merchandises.index') }}" class="general-menu-item button text-green bg-lightgreen is-borderless">
                                <span class="icon is-size-5">
                                    <i class="fas fa-chart-bar"></i>
                                </span>
                            </a>
                            <br>
                            <span class="is-size-6 is-size-7-mobile text-green">
                                Merchandise Inventory
                            </span>
                        </div>
                    @endcan

                    @can('onlyPremiumOrProfessional', userCompany())
                        @can('Read GDN')
                            <div class="column is-4 has-text-centered has-text-grey">
                                <a href="{{ route('gdns.index') }}" class="general-menu-item button text-green bg-lightgreen is-borderless">
                                    <span class="icon is-size-5">
                                        <i class="fas fa-file-invoice"></i>
                                    </span>
                                </a>
                                <br>
                                <span class="is-size-6 is-size-7-mobile text-green">
                                    DO/GDN
                                </span>
                            </div>
                        @endcan
                        @can('Read GRN')
                            <div class="column is-4 has-text-centered has-text-grey">
                                <a href="{{ route('grns.index') }}" class="general-menu-item button text-green bg-lightgreen is-borderless">
                                    <span class="icon is-size-5">
                                        <i class="fas fa-file-signature"></i>
                                    </span>
                                </a>
                                <br>
                                <span class="is-size-6 is-size-7-mobile text-green">
                                    GRN
                                </span>
                            </div>
                        @endcan
                        @can('Read Transfer')
                            <div class="column is-4 has-text-centered has-text-grey">
                                <a href="{{ route('transfers.index') }}" class="general-menu-item button text-green bg-lightgreen is-borderless">
                                    <span class="icon is-size-5">
                                        <i class="fas fa-exchange-alt"></i>
                                    </span>
                                </a>
                                <br>
                                <span class="is-size-6 is-size-7-mobile text-green">
                                    Transfers
                                </span>
                            </div>
                        @endcan
                        @can('Read SIV')
                            <div class="column is-4 has-text-centered has-text-grey">
                                <a href="{{ route('sivs.index') }}" class="general-menu-item button text-green bg-lightgreen is-borderless">
                                    <span class="icon is-size-5">
                                        <i class="fas fa-file-export"></i>
                                    </span>
                                </a>
                                <br>
                                <span class="is-size-6 is-size-7-mobile text-green">
                                    SIV
                                </span>
                            </div>
                        @endcan
                    @endcan

                    @if (userCompany()->name != 'Scepto Import')
                        @can('Read Sale')
                            <div class="column is-4 has-text-centered has-text-grey">
                                <a href="{{ route('sales.index') }}" class="general-menu-item button text-green bg-lightgreen is-borderless">
                                    <span class="icon is-size-5">
                                        <i class="fas fa-tags"></i>
                                    </span>
                                </a>
                                <br>
                                <span class="is-size-6 is-size-7-mobile text-green">
                                    Sales
                                </span>
                            </div>
                        @endcan
                    @endif

                    @can('Read Price')
                        <div class="column is-4 has-text-centered has-text-grey">
                            <a href="{{ route('prices.index') }}" class="general-menu-item button text-green bg-lightgreen is-borderless">
                                <span class="icon is-size-5">
                                    <i class="fas fa-money-bill"></i>
                                </span>
                            </a>
                            <br>
                            <span class="is-size-6 is-size-7-mobile text-green">
                                Prices
                            </span>
                        </div>
                    @endcan

                    @can('Read Tender')
                        <div class="column is-4 has-text-centered has-text-grey">
                            <a href="{{ route('tenders.index') }}" class="general-menu-item button text-green bg-lightgreen is-borderless">
                                <span class="icon is-size-5">
                                    <i class="fas fa-project-diagram"></i>
                                </span>
                            </a>
                            <br>
                            <span class="is-size-6 is-size-7-mobile text-green">
                                Tenders
                            </span>
                        </div>
                    @endcan

                    @can('onlyPremiumOrProfessional', userCompany())
                        @can('Read Customer')
                            <div class="column is-4 has-text-centered has-text-grey">
                                <a href="{{ route('customers.index') }}" class="general-menu-item button text-green bg-lightgreen is-borderless">
                                    <span class="icon is-size-5">
                                        <i class="fas fa-user"></i>
                                    </span>
                                </a>
                                <br>
                                <span class="is-size-6 is-size-7-mobile text-green">
                                    Customers
                                </span>
                            </div>
                        @endcan
                    @endcan

                    @can('onlyPremiumOrProfessional', userCompany())
                        @can('Read PO')
                            <div class="column is-4 has-text-centered has-text-grey">
                                <a href="{{ route('purchase-orders.index') }}" class="general-menu-item button text-green bg-lightgreen is-borderless">
                                    <span class="icon is-size-5">
                                        <i class="fas fa-file-alt"></i>
                                    </span>
                                </a>
                                <br>
                                <span class="is-size-6 is-size-7-mobile text-green">
                                    Purchase Orders
                                </span>
                            </div>
                        @endcan
                    @endcan

                    @can('Read Purchase')
                        <div class="column is-4 has-text-centered has-text-grey">
                            <a href="{{ route('purchases.index') }}" class="general-menu-item button text-green bg-lightgreen is-borderless">
                                <span class="icon is-size-5">
                                    <i class="fas fa-shopping-bag"></i>
                                </span>
                            </a>
                            <br>
                            <span class="is-size-6 is-size-7-mobile text-green">
                                Purchases
                            </span>
                        </div>
                    @endcan

                    @can('onlyPremiumOrProfessional', userCompany())
                        @can('Read Supplier')
                            <div class="column is-4 has-text-centered has-text-grey">
                                <a href="{{ route('suppliers.index') }}" class="general-menu-item button text-green bg-lightgreen is-borderless">
                                    <span class="icon is-size-5">
                                        <i class="fas fa-address-card"></i>
                                    </span>
                                </a>
                                <br>
                                <span class="is-size-6 is-size-7-mobile text-green">
                                    Suppliers
                                </span>
                            </div>
                        @endcan
                    @endcan

                    @can('Read Product')
                        <div class="column is-4 has-text-centered has-text-grey">
                            <a href="{{ route('categories.index') }}" class="general-menu-item button text-green bg-lightgreen is-borderless">
                                <span class="icon is-size-5">
                                    <i class="fas fa-layer-group"></i>
                                </span>
                            </a>
                            <br>
                            <span class="is-size-6 is-size-7-mobile text-green">
                                Categories
                            </span>
                        </div>
                        <div class="column is-4 has-text-centered has-text-grey">
                            <a href="{{ route('products.index') }}" class="general-menu-item button text-green bg-lightgreen is-borderless">
                                <span class="icon is-size-5">
                                    <i class="fas fa-th"></i>
                                </span>
                            </a>
                            <br>
                            <span class="is-size-6 is-size-7-mobile text-green">
                                Products
                            </span>
                        </div>
                    @endcan

                    @can('onlyPremiumOrProfessional', userCompany())
                        @can('Read Warehouse')
                            <div class="column is-4 has-text-centered has-text-grey">
                                <a href="{{ route('warehouses.index') }}" class="general-menu-item button text-green bg-lightgreen is-borderless">
                                    <span class="icon is-size-5">
                                        <i class="fas fa-warehouse"></i>
                                    </span>
                                </a>
                                <br>
                                <span class="is-size-6 is-size-7-mobile text-green">
                                    Warehouses
                                </span>
                            </div>
                        @endcan
                    @endcan

                    @can('Read Employee')
                        <div class="column is-4 has-text-centered has-text-grey">
                            <a href="{{ route('employees.index') }}" class="general-menu-item button text-green bg-lightgreen is-borderless">
                                <span class="icon is-size-5">
                                    <i class="fas fa-users"></i>
                                </span>
                            </a>
                            <br>
                            <span class="is-size-6 is-size-7-mobile text-green">
                                Employees
                            </span>
                        </div>
                    @endcan

                </div>
            </div>
        </div>
    </div>
@endsection
