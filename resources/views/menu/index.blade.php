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
                        @if ($enabledFeatures->contains('Merchandise Inventory Level'))
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
                        @endif
                    @endcan

                    @can('Read GDN')
                        @if ($enabledFeatures->contains('Gdn'))
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
                        @endif
                    @endcan

                    @can('Read GRN')
                        @if ($enabledFeatures->contains('Grn'))
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
                        @endif
                    @endcan

                    @can('Read Transfer')
                        @if ($enabledFeatures->contains('Transfer'))
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
                        @endif
                    @endcan

                    @can('Read Damage')
                        @if ($enabledFeatures->contains('Damage'))
                            <div class="column is-4 has-text-centered has-text-grey">
                                <a href="{{ route('damages.index') }}" class="general-menu-item button text-green bg-lightgreen is-borderless">
                                    <span class="icon is-size-5">
                                        <i class="fas fa-bolt"></i>
                                    </span>
                                </a>
                                <br>
                                <span class="is-size-6 is-size-7-mobile text-green">
                                    Damages
                                </span>
                            </div>
                        @endif
                    @endcan

                    @can('Read Adjustment')
                        @if ($enabledFeatures->contains('Inventory Adjustments'))
                            <div class="column is-4 has-text-centered has-text-grey">
                                <a href="{{ route('adjustments.index') }}" class="general-menu-item button text-green bg-lightgreen is-borderless">
                                    <span class="icon is-size-5">
                                        <i class="fas fa-eraser"></i>
                                    </span>
                                </a>
                                <br>
                                <span class="is-size-6 is-size-7-mobile text-green">
                                    Adjustments
                                </span>
                            </div>
                        @endif
                    @endcan

                    @can('Read SIV')
                        @if ($enabledFeatures->contains('Siv'))
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
                        @endif
                    @endcan

                    @can('Read Sale')
                        @if ($enabledFeatures->contains('Sales Invoice'))
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
                        @endif
                    @endcan

                    @can('Read Proforma Invoice')
                        @if ($enabledFeatures->contains('Proforma Invoices'))
                            <div class="column is-4 has-text-centered has-text-grey">
                                <a href="{{ route('proforma-invoices.index') }}" class="general-menu-item button text-green bg-lightgreen is-borderless">
                                    <span class="icon is-size-5">
                                        <i class="fas fa-receipt"></i>
                                    </span>
                                </a>
                                <br>
                                <span class="is-size-6 is-size-7-mobile text-green">
                                    Proforma Invoices
                                </span>
                            </div>
                        @endif
                    @endcan

                    @can('Read Price')
                        @if ($enabledFeatures->contains('Price Management'))
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
                        @endif
                    @endcan

                    @can('Read Tender')
                        @if ($enabledFeatures->contains('Tender Management'))
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
                        @endif
                    @endcan

                    @can('Read Customer')
                        @if ($enabledFeatures->contains('Customer Management'))
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
                        @endif
                    @endcan

                    @can('Read PO')
                        @if ($enabledFeatures->contains('Purchase Order'))
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
                        @endif
                    @endcan

                    @can('Read Purchase')
                        @if ($enabledFeatures->contains('Purchase Management'))
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
                        @endif
                    @endcan

                    @can('Read Supplier')
                        @if ($enabledFeatures->contains('Supplier Management'))
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
                        @endif
                    @endcan

                    @can('Read Product')
                        @if ($enabledFeatures->contains('Product Management'))
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
                        @endif
                    @endcan

                    @can('Read Warehouse')
                        @if ($enabledFeatures->contains('Warehouse Management'))
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
                        @endif
                    @endcan

                    @can('Read Employee')
                        @if ($enabledFeatures->contains('User Management'))
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
                        @endif
                    @endcan

                </div>
            </div>
        </div>
    </div>
@endsection
