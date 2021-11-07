@extends('layouts.app')

@section('title', 'Main Menu')

@section('content')
    <x-common.content-wrapper>
        @canany(['Read Merchandise', 'Read Warehouse', 'Read GRN', 'Read Transfer', 'Read Damage', 'Read Adjustment', 'Read SIV'])
            <section class="mb-5">
                <x-content.header>
                    <x-slot name="header">
                        <span class="icon">
                            <i class="fas fa-warehouse"></i>
                        </span>
                        <span class="ml-2">
                            Warehouse & Inventory
                        </span>
                    </x-slot>
                </x-content.header>
                <x-content.footer>
                    <div class="columns is-marginless is-multiline is-mobile">
                        @can('Read Merchandise')
                            @if (isFeatureEnabled('Merchandise Inventory'))
                                <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                    <a
                                        href="{{ route('merchandises.index', 'on-hand') }}"
                                        class="general-menu-item button text-green bg-lightgreen is-borderless"
                                    >
                                        <span class="icon is-size-5">
                                            <i class="fas fa-chart-bar"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-6 is-size-7-mobile text-green">
                                        Inventory Level
                                    </span>
                                </div>
                            @endif
                        @endcan

                        @can('Read Warehouse')
                            @if (isFeatureEnabled('Warehouse Management'))
                                <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                    <a
                                        href="{{ route('warehouses.index') }}"
                                        class="general-menu-item button text-green bg-lightgreen is-borderless"
                                    >
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

                        @can('Read GRN')
                            @if (isFeatureEnabled('Grn Management'))
                                <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                    <a
                                        href="{{ route('grns.index') }}"
                                        class="general-menu-item button text-green bg-lightgreen is-borderless"
                                    >
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
                            @if (isFeatureEnabled('Transfer Management'))
                                <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                    <a
                                        href="{{ route('transfers.index') }}"
                                        class="general-menu-item button text-green bg-lightgreen is-borderless"
                                    >
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
                            @if (isFeatureEnabled('Damage Management'))
                                <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                    <a
                                        href="{{ route('damages.index') }}"
                                        class="general-menu-item button text-green bg-lightgreen is-borderless"
                                    >
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
                            @if (isFeatureEnabled('Inventory Adjustment'))
                                <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                    <a
                                        href="{{ route('adjustments.index') }}"
                                        class="general-menu-item button text-green bg-lightgreen is-borderless"
                                    >
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
                            @if (isFeatureEnabled('Siv Management'))
                                <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                    <a
                                        href="{{ route('sivs.index') }}"
                                        class="general-menu-item button text-green bg-lightgreen is-borderless"
                                    >
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
                    </div>
                </x-content.footer>
            </section>
        @endcanany

        @canany(['Read Sale', 'Read GDN', 'Read Proforma Invoice', 'Read Reservation', 'Read Return', 'Read PO', 'Read Customer'])
            <section class="mb-5">
                <x-content.header>
                    <x-slot name="header">
                        <span class="icon">
                            <i class="fas fa-tags"></i>
                        </span>
                        <span class="ml-2">
                            Sales & Customers
                        </span>
                    </x-slot>
                </x-content.header>
                <x-content.footer>
                    <div class="columns is-marginless is-multiline is-mobile">
                        @can('Read Sale')
                            @if (isFeatureEnabled('Sale Management'))
                                <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                    <a
                                        href="{{ route('sales.index') }}"
                                        class="general-menu-item button text-green bg-lightgreen is-borderless"
                                    >
                                        <span class="icon is-size-5">
                                            <i class="fas fa-tags"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-6 is-size-7-mobile text-green">
                                        Invoices
                                    </span>
                                </div>
                            @endif
                        @endcan

                        @can('Read GDN')
                            @if (isFeatureEnabled('Gdn Management'))
                                <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                    <a
                                        href="{{ route('gdns.index') }}"
                                        class="general-menu-item button text-green bg-lightgreen is-borderless"
                                    >
                                        <span class="icon is-size-5">
                                            <i class="fas fa-file-invoice"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-6 is-size-7-mobile text-green">
                                        Delivery Orders
                                    </span>
                                </div>
                            @endif
                        @endcan

                        @can('Read Proforma Invoice')
                            @if (isFeatureEnabled('Proforma Invoice'))
                                <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                    <a
                                        href="{{ route('proforma-invoices.index') }}"
                                        class="general-menu-item button text-green bg-lightgreen is-borderless"
                                    >
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

                        @can('Read Reservation')
                            @if (isFeatureEnabled('Reservation Management'))
                                <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                    <a
                                        href="{{ route('reservations.index') }}"
                                        class="general-menu-item button text-green bg-lightgreen is-borderless"
                                    >
                                        <span class="icon is-size-5">
                                            <i class="fas fa-archive"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-6 is-size-7-mobile text-green">
                                        Reservations
                                    </span>
                                </div>
                            @endif
                        @endcan

                        @can('Read Return')
                            @if (isFeatureEnabled('Return Management'))
                                <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                    <a
                                        href="{{ route('returns.index') }}"
                                        class="general-menu-item button text-green bg-lightgreen is-borderless"
                                    >
                                        <span class="icon is-size-5">
                                            <i class="fas fa-arrow-alt-circle-left"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-6 is-size-7-mobile text-green">
                                        Returns
                                    </span>
                                </div>
                            @endif
                        @endcan

                        @can('Read PO')
                            @if (isFeatureEnabled('Purchase Order'))
                                <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                    <a
                                        href="{{ route('purchase-orders.index') }}"
                                        class="general-menu-item button text-green bg-lightgreen is-borderless"
                                    >
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

                        @can('Read Credit')
                            @if (isFeatureEnabled('Credit Management'))
                                <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                    <a
                                        href="{{ route('credits.index') }}"
                                        class="general-menu-item button text-green bg-lightgreen is-borderless"
                                    >
                                        <span class="icon is-size-5">
                                            <i class="fas fa-money-check"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-6 is-size-7-mobile text-green">
                                        Credits
                                    </span>
                                </div>
                            @endif
                        @endcan

                        @can('Read Customer')
                            @if (isFeatureEnabled('Customer Management'))
                                <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                    <a
                                        href="{{ route('customers.index') }}"
                                        class="general-menu-item button text-green bg-lightgreen is-borderless"
                                    >
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
                    </div>
                </x-content.footer>
            </section>
        @endcanany

        @can('Read Tender')
            <section class="mb-5">
                <x-content.header>
                    <x-slot name="header">
                        <span class="icon">
                            <i class="fas fa-project-diagram"></i>
                        </span>
                        <span class="ml-2">
                            Tenders
                        </span>
                    </x-slot>
                </x-content.header>
                <x-content.footer>
                    <div class="columns is-marginless is-multiline is-mobile">
                        @can('Read Tender')
                            @if (isFeatureEnabled('Tender Management'))
                                <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                    <a
                                        href="{{ route('tenders.index') }}"
                                        class="general-menu-item button text-green bg-lightgreen is-borderless"
                                    >
                                        <span class="icon is-size-5">
                                            <i class="fas fa-project-diagram"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-6 is-size-7-mobile text-green">
                                        Tenders
                                    </span>
                                </div>
                                <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                    <a
                                        href="{{ route('tender-checklist-types.index') }}"
                                        class="general-menu-item button text-green bg-lightgreen is-borderless"
                                    >
                                        <span class="icon is-size-5">
                                            <i class="fas fa-tasks"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-6 is-size-7-mobile text-green">
                                        Checklist Categories
                                    </span>
                                </div>
                                <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                    <a
                                        href="{{ route('general-tender-checklists.index') }}"
                                        class="general-menu-item button text-green bg-lightgreen is-borderless"
                                    >
                                        <span class="icon is-size-5">
                                            <i class="fas fa-check-double"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-6 is-size-7-mobile text-green">
                                        Available Checklists
                                    </span>
                                </div>
                                <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                    <a
                                        href="{{ route('tender-statuses.index') }}"
                                        class="general-menu-item button text-green bg-lightgreen is-borderless"
                                    >
                                        <span class="icon is-size-5">
                                            <i class="fas fa-info"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-6 is-size-7-mobile text-green">
                                        Statuses
                                    </span>
                                </div>
                            @endif
                        @endcan
                    </div>
                </x-content.footer>
            </section>
        @endcan

        @canany(['Read Purchase', 'Read Supplier'])
            <section class="mb-5">
                <x-content.header>
                    <x-slot name="header">
                        <span class="icon">
                            <i class="fas fa-shopping-bag"></i>
                        </span>
                        <span class="ml-2">
                            Purchases & Suppliers
                        </span>
                    </x-slot>
                </x-content.header>
                <x-content.footer>
                    <div class="columns is-marginless is-multiline is-mobile">
                        @can('Read Purchase')
                            @if (isFeatureEnabled('Purchase Management'))
                                <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                    <a
                                        href="{{ route('purchases.index') }}"
                                        class="general-menu-item button text-green bg-lightgreen is-borderless"
                                    >
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
                            @if (isFeatureEnabled('Supplier Management'))
                                <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                    <a
                                        href="{{ route('suppliers.index') }}"
                                        class="general-menu-item button text-green bg-lightgreen is-borderless"
                                    >
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
                    </div>
                </x-content.footer>
            </section>
        @endcanany

        @can('Read Product')
            <section class="mb-5">
                <x-content.header>
                    <x-slot name="header">
                        <span class="icon">
                            <i class="fas fa-th"></i>
                        </span>
                        <span class="ml-2">
                            Products & Categories
                        </span>
                    </x-slot>
                </x-content.header>
                <x-content.footer>
                    <div class="columns is-marginless is-multiline is-mobile">
                        @can('Read Product')
                            @if (isFeatureEnabled('Product Management'))
                                <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                    <a
                                        href="{{ route('categories.index') }}"
                                        class="general-menu-item button text-green bg-lightgreen is-borderless"
                                    >
                                        <span class="icon is-size-5">
                                            <i class="fas fa-layer-group"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-6 is-size-7-mobile text-green">
                                        Categories
                                    </span>
                                </div>
                                <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                    <a
                                        href="{{ route('products.index') }}"
                                        class="general-menu-item button text-green bg-lightgreen is-borderless"
                                    >
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
                    </div>
                </x-content.footer>
            </section>
        @endcan

        @canany(['Read Employee', 'Update Company'])
            <section>
                <x-content.header>
                    <x-slot name="header">
                        <span class="icon">
                            <i class="fas fa-cog"></i>
                        </span>
                        <span class="ml-2">
                            General Settings
                        </span>
                    </x-slot>
                </x-content.header>
                <x-content.footer>
                    <div class="columns is-marginless is-multiline is-mobile">
                        @can('Read Employee')
                            @if (isFeatureEnabled('User Management'))
                                <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                    <a
                                        href="{{ route('employees.index') }}"
                                        class="general-menu-item button text-green bg-lightgreen is-borderless"
                                    >
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

                        @can('Update Company')
                            @if (isFeatureEnabled('General Settings'))
                                <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                    <a
                                        href="{{ route('companies.edit', userCompany()->id) }}"
                                        class="general-menu-item button text-green bg-lightgreen is-borderless"
                                    >
                                        <span class="icon is-size-5">
                                            <i class="fas fa-building"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-6 is-size-7-mobile text-green">
                                        Company Profile
                                    </span>
                                </div>
                            @endif
                        @endcan
                    </div>
                </x-content.footer>
            </section>
        @endcanany
    </x-common.content-wrapper>
@endsection
