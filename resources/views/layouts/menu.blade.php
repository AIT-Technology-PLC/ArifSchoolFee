<aside class="menu">
    <ul class="menu-list has-text-centered">
        <li>
            <figure class="image is-64x64" style="margin: auto !important">
                <img class="is-rounded" src="{{ asset('img/user.jpg') }}">
            </figure>
            <div class="has-text-weight-bold mt-3 is-capitalized">
                {{ auth()->user()->name }}
            </div>
            <div class="has-text-grey has-text-weight-bold is-size-6-5 is-capitalized">
                {{ auth()->user()->employee->position ?? 'Job: Not Assigned' }}
            </div>
            <div class="buttons is-centered mt-4 is-hidden-tablet">
                <a class="button bg-green has-text-white is-small" href="{{ route('employees.show', auth()->user()->employee->id) }}">
                    <span class="icon">
                        <i class="fas fa-address-card"></i>
                    </span>
                    <span>
                        My Profile
                    </span>
                </a>
                <a class="button btn-purple is-outlined is-small" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <span class="icon">
                        <i class="fas fa-power-off"></i>
                    </span>
                    <span>
                        Logout
                    </span>
                </a>
                <form action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>
    </ul>

    <hr>

    <p class="menu-label has-text-weight-bold text-green">
        Menu
    </p>
    <ul class="menu-list mb-5">
        <li>
            <a name="menuTitles" href="/" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('/') ? 'is-active' : '' }}">
                <span class="icon">
                    <i class="fas fa-bars"></i>
                </span>
                <span>
                    General Menu
                </span>
            </a>
        </li>
    </ul>

    @can('Read Merchandise')
        @if ($enabledFeatures->contains('Merchandise Inventory'))
            <p class="menu-label has-text-weight-bold text-green">
                Merchandise Inventory
            </p>
            <ul class="menu-list mb-5">
                <li>
                    <a name="menuTitles" href="{{ route('merchandises.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5  {{ request()->is('merchandises') ? 'is-active' : '' }}">
                        <span class="icon">
                            <i class="fas fa-chart-bar"></i>
                        </span>
                        <span>
                            Inventory Level
                        </span>
                    </a>
                </li>
            </ul>
        @endif
    @endcan

    @canany(['Read GDN', 'Read GRN', 'Read Transfer', 'Read Damage', 'Read Adjustment', 'Read SIV'])
        <p class="menu-label has-text-weight-bold text-green">
            Warehouse Operations
        </p>
        <ul class="menu-list mb-5">
            @if ($enabledFeatures->contains('Gdn Management'))
                @can('Read GDN')
                    <li>
                        <a name="menuTitles" href="{{ route('gdns.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('gdns') ? 'is-active' : '' }}">
                            <span class="icon">
                                <i class="fas fa-file-invoice"></i>
                            </span>
                            <span>
                                DO/GDN Management
                            </span>
                        </a>
                    </li>
                @endcan
                @can('Create GDN')
                    <li>
                        <ul class="mt-0">
                            <li>
                                <a name="menuTitles" href="{{ route('gdns.create') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('gdns/create') ? 'is-active' : '' }}">
                                    <span class="icon">
                                        <i class="fas fa-plus-circle"></i>
                                    </span>
                                    <span>
                                        New Products Out
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
            @endif
            @if ($enabledFeatures->contains('Grn Management'))
                @can('Read GRN')
                    <li>
                        <a name="menuTitles" href="{{ route('grns.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('grns') ? 'is-active' : '' }}">
                            <span class="icon">
                                <i class="fas fa-file-contract"></i>
                            </span>
                            <span>
                                GRN Management
                            </span>
                        </a>
                    </li>
                @endcan
                @can('Create GRN')
                    <li>
                        <ul class="mt-0">
                            <li>
                                <a name="menuTitles" href="{{ route('grns.create') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('grns/create') ? 'is-active' : '' }}">
                                    <span class="icon">
                                        <i class="fas fa-plus-circle"></i>
                                    </span>
                                    <span>
                                        New Products In
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
            @endif
            @if ($enabledFeatures->contains('Transfer Management'))
                @can('Read Transfer')
                    <li>
                        <a name="menuTitles" href="{{ route('transfers.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('transfers') ? 'is-active' : '' }}">
                            <span class="icon">
                                <i class="fas fa-exchange-alt"></i>
                            </span>
                            <span>
                                Transfer Management
                            </span>
                        </a>
                    </li>
                @endcan
                @can('Create Transfer')
                    <li>
                        <ul class="mt-0">
                            <li>
                                <a name="menuTitles" href="{{ route('transfers.create') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('transfers/create') ? 'is-active' : '' }}">
                                    <span class="icon">
                                        <i class="fas fa-plus-circle"></i>
                                    </span>
                                    <span>
                                        New Transfer
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
            @endif
            @if ($enabledFeatures->contains('Damage Management'))
                @can('Read Damage')
                    <li>
                        <a name="menuTitles" href="{{ route('damages.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('damages') ? 'is-active' : '' }}">
                            <span class="icon">
                                <i class="fas fa-bolt"></i>
                            </span>
                            <span>
                                Damage Management
                            </span>
                            <sup class="text-purple has-text-weight-bold">NEW</sup>
                        </a>
                    </li>
                @endcan
                @can('Create Damage')
                    <li>
                        <ul class="mt-0">
                            <li>
                                <a name="menuTitles" href="{{ route('damages.create') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('damages/create') ? 'is-active' : '' }}">
                                    <span class="icon">
                                        <i class="fas fa-plus-circle"></i>
                                    </span>
                                    <span>
                                        New Damage
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
            @endif
            @if ($enabledFeatures->contains('Inventory Adjustment'))
                @can('Read Adjustment')
                    <li>
                        <a name="menuTitles" href="{{ route('adjustments.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('adjustments') ? 'is-active' : '' }}">
                            <span class="icon">
                                <i class="fas fa-eraser"></i>
                            </span>
                            <span>
                                Inventory Adjustments
                            </span>
                            <sup class="text-purple has-text-weight-bold">NEW</sup>
                        </a>
                    </li>
                @endcan
                @can('Create Adjustment')
                    <li>
                        <ul class="mt-0">
                            <li>
                                <a name="menuTitles" href="{{ route('adjustments.create') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('adjustments/create') ? 'is-active' : '' }}">
                                    <span class="icon">
                                        <i class="fas fa-plus-circle"></i>
                                    </span>
                                    <span>
                                        New Adjustment
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
            @endif
            @if ($enabledFeatures->contains('Siv Management'))
                @can('Read SIV')
                    <li>
                        <a name="menuTitles" href="{{ route('sivs.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('sivs') ? 'is-active' : '' }}">
                            <span class="icon">
                                <i class="fas fa-file-export"></i>
                            </span>
                            <span>
                                SIV Management
                            </span>
                            <sup class="text-purple has-text-weight-bold">NEW</sup>
                        </a>
                    </li>
                @endcan
                @can('Create SIV')
                    <li>
                        <ul class="mt-0">
                            <li>
                                <a name="menuTitles" href="{{ route('sivs.create') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('sivs/create') ? 'is-active' : '' }}">
                                    <span class="icon">
                                        <i class="fas fa-plus-circle"></i>
                                    </span>
                                    <span>
                                        New SIV
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
            @endif
        </ul>
    @endcanany

    @canany(['Read Sale', 'Read Proforma Invoice', 'Read Price', 'Read Customer', 'Read Reservation'])
        <p class="menu-label has-text-weight-bold text-green">
            Sales & Customers
        </p>
        <ul class="menu-list mb-5">
            @if ($enabledFeatures->contains('Sale Management'))
                @can('Read Sale')
                    <li>
                        <a name="menuTitles" href="{{ route('sales.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('sales') ? 'is-active' : '' }}">
                            <span class="icon">
                                <i class="fas fa-tags"></i>
                            </span>
                            <span>
                                Sales Management
                            </span>
                        </a>
                    </li>
                @endcan
                @can('Create Sale')
                    <li>
                        <ul class="mt-0">
                            <li>
                                <a name="menuTitles" href="{{ route('sales.create') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('sales/create') ? 'is-active' : '' }}">
                                    <span class="icon">
                                        <i class="fas fa-plus-circle"></i>
                                    </span>
                                    <span>
                                        New Sales
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
            @endif
            @if ($enabledFeatures->contains('Proforma Invoice'))
                @can('Read Proforma Invoice')
                    <li>
                        <a name="menuTitles" href="{{ route('proforma-invoices.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('proforma-invoices') ? 'is-active' : '' }}">
                            <span class="icon">
                                <i class="fas fa-receipt"></i>
                            </span>
                            <span>
                                Proforma Invoices
                            </span>
                            <sup class="text-purple has-text-weight-bold">NEW</sup>
                        </a>
                    </li>
                @endcan
                @can('Create Proforma Invoice')
                    <li>
                        <ul class="mt-0">
                            <li>
                                <a name="menuTitles" href="{{ route('proforma-invoices.create') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('proforma-invoices/create') ? 'is-active' : '' }}">
                                    <span class="icon">
                                        <i class="fas fa-plus-circle"></i>
                                    </span>
                                    <span>
                                        New Proforma Invoice
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
            @endif
            @if ($enabledFeatures->contains('Reservation Management'))
                @can('Read Reservation')
                    <li>
                        <a name="menuTitles" href="{{ route('reservations.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('reservations') ? 'is-active' : '' }}">
                            <span class="icon">
                                <i class="fas fa-archive"></i>
                            </span>
                            <span>
                                Reservation Management
                            </span>
                        </a>
                    </li>
                @endcan
                @can('Create Reservation')
                    <li>
                        <ul class="mt-0">
                            <li>
                                <a name="menuTitles" href="{{ route('reservations.create') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('reservations/create') ? 'is-active' : '' }}">
                                    <span class="icon">
                                        <i class="fas fa-plus-circle"></i>
                                    </span>
                                    <span>
                                        New Reservation
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
            @endif
            @if ($enabledFeatures->contains('Return Management'))
                @can('Read Return')
                    <li>
                        <a name="menuTitles" href="{{ route('returns.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('returns') ? 'is-active' : '' }}">
                            <span class="icon">
                                <i class="fas fa-arrow-alt-circle-left"></i>
                            </span>
                            <span>
                                Returns Management
                            </span>
                            <sup class="text-purple has-text-weight-bold">NEW</sup>
                        </a>
                    </li>
                @endcan
                @can('Create Return')
                    <li>
                        <ul class="mt-0">
                            <li>
                                <a name="menuTitles" href="{{ route('returns.create') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('returns/create') ? 'is-active' : '' }}">
                                    <span class="icon">
                                        <i class="fas fa-plus-circle"></i>
                                    </span>
                                    <span>
                                        New Return
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
            @endif
            @if ($enabledFeatures->contains('Customer Management'))
                @can('Read Customer')
                    <li>
                        <a name="menuTitles" href="{{ route('customers.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('customers') ? 'is-active' : '' }}">
                            <span class="icon">
                                <i class="fas fa-user-alt"></i>
                            </span>
                            <span>
                                Customer Management
                            </span>
                        </a>
                    </li>
                @endcan
                @can('Create Customer')
                    <li>
                        <ul class="mt-0">
                            <li>
                                <a name="menuTitles" href="{{ route('customers.create') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('customers/create') ? 'is-active' : '' }}">
                                    <span class="icon">
                                        <i class="fas fa-plus-circle"></i>
                                    </span>
                                    <span>
                                        New Customer
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
            @endif
            @if ($enabledFeatures->contains('Price Management'))
                @can('Read Price')
                    <li>
                        <a name="menuTitles" href="{{ route('prices.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('prices') ? 'is-active' : '' }}">
                            <span class="icon">
                                <i class="fas fa-money-bill"></i>
                            </span>
                            <span>
                                Price Management
                            </span>
                        </a>
                    </li>
                @endcan
                @can('Create Price')
                    <li>
                        <ul class="mt-0">
                            <li>
                                <a name="menuTitles" href="{{ route('prices.create') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('prices/create') ? 'is-active' : '' }}">
                                    <span class="icon">
                                        <i class="fas fa-plus-circle"></i>
                                    </span>
                                    <span>
                                        New Price
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
            @endif
        </ul>
    @endcanany

    @can('Read Tender')
        @if ($enabledFeatures->contains('Tender Management'))
            <p class="menu-label has-text-weight-bold text-green">
                Tenders
            </p>
            <ul class="menu-list mb-5">
                <li>
                    <a name="menuTitles" href="{{ route('tenders.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('tenders') ? 'is-active' : '' }}">
                        <span class="icon">
                            <i class="fas fa-project-diagram"></i>
                        </span>
                        <span>
                            Tender Management
                        </span>
                    </a>
                </li>
                @can('Create Tender')
                    <li>
                        <ul class="mt-0">
                            <li>
                                <a name="menuTitles" href="{{ route('tenders.create') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('tenders/create') ? 'is-active' : '' }}">
                                    <span class="icon">
                                        <i class="fas fa-plus-circle"></i>
                                    </span>
                                    <span>
                                        New Tender
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                <li>
                    <a name="menuTitles" href="{{ route('general-tender-checklists.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('general-tender-checklists') ? 'is-active' : '' }}">
                        <span class="icon">
                            <i class="fas fa-tasks"></i>
                        </span>
                        <span>
                            Tender Checklist
                        </span>
                    </a>
                </li>
                @can('Create Tender')
                    <li>
                        <ul class="mt-0">
                            <li>
                                <a name="menuTitles" href="{{ route('general-tender-checklists.create') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('general-tender-checklists/create') ? 'is-active' : '' }}">
                                    <span class="icon">
                                        <i class="fas fa-plus-circle"></i>
                                    </span>
                                    <span>
                                        New Checklist
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                <li>
                    <a name="menuTitles" href="{{ route('tender-statuses.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('tender-statuses') ? 'is-active' : '' }}">
                        <span class="icon">
                            <i class="fas fa-info"></i>
                        </span>
                        <span>
                            Tender Status
                        </span>
                    </a>
                </li>
                @can('Create Tender')
                    <li>
                        <ul class="mt-0">
                            <li>
                                <a name="menuTitles" href="{{ route('tender-statuses.create') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('tender-statuses/create') ? 'is-active' : '' }}">
                                    <span class="icon">
                                        <i class="fas fa-plus-circle"></i>
                                    </span>
                                    <span>
                                        New Status
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
            </ul>
        @endif
    @endcan

    @can('Read PO')
        @if ($enabledFeatures->contains('Purchase Order'))
            <p class="menu-label has-text-weight-bold text-green">
                Purchase Orders
            </p>
            <ul class="menu-list mb-5">
                <li>
                    <a name="menuTitles" href="{{ route('purchase-orders.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('purchase-orders') ? 'is-active' : '' }}">
                        <span class="icon">
                            <i class="fas fa-file-alt"></i>
                        </span>
                        <span>
                            PO Management
                        </span>
                    </a>
                </li>
                @can('Create PO')
                    <li>
                        <ul class="mt-0">
                            <li>
                                <a name="menuTitles" href="{{ route('purchase-orders.create') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('purchase-orders/create') ? 'is-active' : '' }}">
                                    <span class="icon">
                                        <i class="fas fa-plus-circle"></i>
                                    </span>
                                    <span>
                                        New PO
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
            </ul>
        @endif
    @endcan

    @canany(['Read Purchase', 'Read Supplier'])
        <p class="menu-label has-text-weight-bold text-green">
            Purchases & Suppliers
        </p>
        <ul class="menu-list mb-5">
            @if ($enabledFeatures->contains('Purchase Management'))
                @can('Read Purchase')
                    <li>
                        <a name="menuTitles" href="{{ route('purchases.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('purchases') ? 'is-active' : '' }}">
                            <span class="icon">
                                <i class="fas fa-shopping-bag"></i>
                            </span>
                            <span>
                                Purchase Management
                            </span>
                        </a>
                    </li>
                @endcan
                @can('Create Purchase')
                    <li>
                        <ul class="mt-0">
                            <li>
                                <a name="menuTitles" href="{{ route('purchases.create') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('purchases/create') ? 'is-active' : '' }}">
                                    <span class="icon">
                                        <i class="fas fa-plus-circle"></i>
                                    </span>
                                    <span>
                                        New Purchase
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
            @endif
            @if ($enabledFeatures->contains('Supplier Management'))
                @can('Read Supplier')
                    <li>
                        <a name="menuTitles" href="{{ route('suppliers.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('suppliers') ? 'is-active' : '' }}">
                            <span class="icon">
                                <i class="fas fa-address-card"></i>
                            </span>
                            <span>
                                Supplier Management
                            </span>
                        </a>
                    </li>
                @endcan
                @can('Create Supplier')
                    <li>
                        <ul class="mt-0">
                            <li>
                                <a name="menuTitles" href="{{ route('suppliers.create') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('suppliers/create') ? 'is-active' : '' }}">
                                    <span class="icon">
                                        <i class="fas fa-plus-circle"></i>
                                    </span>
                                    <span>
                                        New Supplier
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
            @endif
        </ul>
    @endcanany

    @can('Read Product')
        @if ($enabledFeatures->contains('Product Management'))
            <p class="menu-label has-text-weight-bold text-green">
                Category & Products
            </p>
            <ul class="menu-list mb-5">
                <li>
                    <a name="menuTitles" href="{{ route('categories.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('categories') ? 'is-active' : '' }}">
                        <span class="icon">
                            <i class="fas fa-layer-group"></i>
                        </span>
                        <span>
                            Categories Management
                        </span>
                    </a>
                </li>
                @can('Create Product')
                    <li>
                        <ul class="mt-0">
                            <li>
                                <a name="menuTitles" href="{{ route('categories.create') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('categories/create') ? 'is-active' : '' }}">
                                    <span class="icon">
                                        <i class="fas fa-plus-circle"></i>
                                    </span>
                                    <span>
                                        New Category
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                <li>
                    <a name="menuTitles" href="{{ route('products.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('products') ? 'is-active' : '' }}">
                        <span class="icon">
                            <i class="fas fa-th"></i>
                        </span>
                        <span>
                            Products Management
                        </span>
                    </a>
                </li>
                @can('Create Product')
                    <li>
                        <ul class="mt-0">
                            <li>
                                <a name="menuTitles" href="/products/create" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('products/create') ? 'is-active' : '' }}">
                                    <span class="icon">
                                        <i class="fas fa-plus-circle"></i>
                                    </span>
                                    <span>
                                        New Product
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
            </ul>
        @endif
    @endcan

    @can('Read Warehouse')
        @if ($enabledFeatures->contains('Warehouse Management'))
            <p class="menu-label has-text-weight-bold text-green">
                Warehouse
            </p>
            <ul class="menu-list mb-5">
                <li>
                    <a name="menuTitles" href="{{ route('warehouses.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('warehouses') ? 'is-active' : '' }}">
                        <span class="icon">
                            <i class="fas fa-warehouse"></i>
                        </span>
                        <span>
                            Warehouse Management
                        </span>
                    </a>
                </li>
                @can('Create Warehouse')
                    <li>
                        <ul class="mt-0">
                            <li>
                                <a name="menuTitles" href="{{ route('warehouses.create') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('warehouses/create') ? 'is-active' : '' }}">
                                    <span class="icon">
                                        <i class="fas fa-plus-circle"></i>
                                    </span>
                                    <span>
                                        New Warehouse
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
            </ul>
        @endif
    @endcan

    @canany(['Read Employee', 'Update Company'])
        <p class="menu-label has-text-weight-bold text-green">
            Settings
        </p>
        <ul class="menu-list mb-5">
            @if ($enabledFeatures->contains('User Management'))
                @can('Read Employee')
                    <li>
                        <a name="menuTitles" href="{{ route('employees.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('employees') ? 'is-active' : '' }}">
                            <span class="icon">
                                <i class="fas fa-users"></i>
                            </span>
                            <span>
                                Employee Management
                            </span>
                        </a>
                    </li>
                @endcan
                @can('Create Employee')
                    <li>
                        <ul class="mt-0">
                            <li>
                                <a name="menuTitles" href="{{ route('employees.create') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('employees/create') ? 'is-active' : '' }}">
                                    <span class="icon">
                                        <i class="fas fa-plus-circle"></i>
                                    </span>
                                    <span>
                                        New Employee
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
            @endif
            @if ($enabledFeatures->contains('General Settings'))
                @can('Update Company')
                    <li>
                        <a name="menuTitles" href="{{ route('companies.edit', userCompany()->id) }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('companies/' . userCompany()->id . '/edit') ? 'is-active' : '' }}">
                            <span class="icon">
                                <i class="fas fa-cog"></i>
                            </span>
                            <span>
                                General Settings
                            </span>
                        </a>
                    </li>
                @endcan
            @endif
        </ul>
    @endcanany
</aside>
