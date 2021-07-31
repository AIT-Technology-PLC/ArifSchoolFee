<aside class="menu">
    <ul class="menu-list">
        <li>
            <div class="columns is-marginless is-mobile">
                <div class="column is-3-mobile is-4-tablet p-0">
                    <figure class="image is-64x64" style="margin: auto !important">
                        <img class="is-rounded" src="{{ asset('img/user.jpg') }}">
                    </figure>
                </div>
                <div class="column p-0">
                    <div class="has-text-weight-medium has-text-black mt-3 is-capitalized">
                        {{ auth()->user()->name }}
                    </div>
                    <div class="has-text-grey is-size-7 is-capitalized">
                        {{ auth()->user()->employee->position ?? 'Job: Not Assigned' }}
                    </div>
                </div>
            </div>
            <div class="buttons is-hidden-tablet ml-3 mt-3">
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

    <ul class="menu-list mb-2">
        <li>
            <a name="menuTitles" href="/" class="text-green is-size-6-5 has-text-left {{ request()->is('/') ? 'is-active' : '' }}">
                <span class="icon pl-1">
                    <i class="fas fa-bars"></i>
                </span>
                <span class="ml-1">
                    Main Menu
                </span>
            </a>
        </li>
    </ul>

    @canany(['Read Merchandise', 'Read Warehouse', 'Read GRN', 'Read Transfer', 'Read Damage', 'Read Adjustment', 'Read SIV'])
        <ul class="menu-list mb-2">
            <li>
                <button name="menu-accordion" class="button is-borderless text-green is-size-6-5 ml-0">
                    <span class="icon m-0">
                        <i class="fas fa-warehouse"></i>
                    </span>
                    <span class="ml-2">
                        Warehouse & Inventory
                    </span>
                </button>
            </li>
            <li>
                <ul class="mt-0 ml-5 is-hidden">
                    @can('Read Merchandise')
                        @if ($enabledFeatures->contains('Merchandise Inventory'))
                            <li>
                                <a name="menuTitles" href="{{ route('merchandises.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('merchandises') ? 'is-active' : '' }}">
                                    Inventory Level
                                </a>
                            </li>
                        @endif
                    @endcan
                    @can('Read Warehouse')
                        @if ($enabledFeatures->contains('Warehouse Management'))
                            <li>
                                <a name="menuTitles" href="{{ route('warehouses.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('warehouses') ? 'is-active' : '' }}">
                                    Warehouses
                                </a>
                            </li>
                        @endif
                    @endcan
                    @if ($enabledFeatures->contains('Grn Management'))
                        @can('Read GRN')
                            <li>
                                <a name="menuTitles" href="{{ route('grns.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('grns') ? 'is-active' : '' }}">
                                    Goods Received Note
                                </a>
                            </li>
                        @endif
                    @endcan
                    @if ($enabledFeatures->contains('Transfer Management'))
                        @can('Read Transfer')
                            <li>
                                <a name="menuTitles" href="{{ route('transfers.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('transfers') ? 'is-active' : '' }}">
                                    Transfers
                                </a>
                            </li>
                        @endif
                    @endcan
                    @if ($enabledFeatures->contains('Damage Management'))
                        @can('Read Damage')
                            <li>
                                <a name="menuTitles" href="{{ route('damages.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('damages') ? 'is-active' : '' }}">
                                    Damages
                                </a>
                            </li>
                        @endcan
                    @endif
                    @if ($enabledFeatures->contains('Inventory Adjustment'))
                        @can('Read Adjustment')
                            <li>
                                <a name="menuTitles" href="{{ route('adjustments.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('adjustments') ? 'is-active' : '' }}">
                                    Adjustments
                                </a>
                            </li>
                        @endcan
                    @endif
                    @if ($enabledFeatures->contains('Siv Management'))
                        @can('Read SIV')
                            <li>
                                <a name="menuTitles" href="{{ route('sivs.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('sivs') ? 'is-active' : '' }}">
                                    Store Issue Voucher
                                </a>
                            </li>
                        @endcan
                    @endif
                </ul>
            </li>
        </ul>
    @endcanany

    @canany(['Read Sale', 'Read GDN', 'Read Proforma Invoice', 'Read Reservation', 'Read Return', 'Read PO', 'Read Customer', 'Read Price'])
        <ul class="menu-list mb-2">
            <li>
                <button name="menu-accordion" class="button is-borderless text-green is-size-6-5 ml-0">
                    <span class="icon m-0">
                        <i class="fas fa-tags"></i>
                    </span>
                    <span class="ml-2">
                        Sales & Cutomers
                    </span>
                </button>
            </li>
            <li>
                <ul class="mt-0 ml-5 is-hidden">
                    @if ($enabledFeatures->contains('Sale Management'))
                        @can('Read Sale')
                            <li>
                                <a name="menuTitles" href="{{ route('sales.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('sales') ? 'is-active' : '' }}">
                                    Invoices
                                </a>
                            </li>
                        @endcan
                    @endif
                    @if ($enabledFeatures->contains('Gdn Management'))
                        @can('Read GDN')
                            <li>
                                <a name="menuTitles" href="{{ route('gdns.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('gdns') ? 'is-active' : '' }}">
                                    Delivery Orders
                                </a>
                            </li>
                        @endcan
                    @endif
                    @if ($enabledFeatures->contains('Proforma Invoice'))
                        @can('Read Proforma Invoice')
                            <li>
                                <a name="menuTitles" href="{{ route('proforma-invoices.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('proforma-invoices') ? 'is-active' : '' }}">
                                    Proforma Invoices
                                </a>
                            </li>
                        @endcan
                    @endif
                    @if ($enabledFeatures->contains('Reservation Management'))
                        @can('Read Reservation')
                            <li>
                                <a name="menuTitles" href="{{ route('reservations.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('reservations') ? 'is-active' : '' }}">
                                    Reservations
                                </a>
                            </li>
                        @endcan
                    @endif
                    @if ($enabledFeatures->contains('Return Management'))
                        @can('Read Return')
                            <li>
                                <a name="menuTitles" href="{{ route('returns.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('returns') ? 'is-active' : '' }}">
                                    Returns
                                </a>
                            </li>
                        @endcan
                    @endif
                    @can('Read PO')
                        @if ($enabledFeatures->contains('Purchase Order'))
                            <li>
                                <a name="menuTitles" href="{{ route('purchase-orders.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('purchase-orders') ? 'is-active' : '' }}">
                                    Purchase Orders
                                </a>
                            </li>
                        @endcan
                    @endif
                    @if ($enabledFeatures->contains('Customer Management'))
                        @can('Read Customer')
                            <li>
                                <a name="menuTitles" href="{{ route('customers.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('customers') ? 'is-active' : '' }}">
                                    Customers
                                </a>
                            </li>
                        @endcan
                    @endif
                    @if ($enabledFeatures->contains('Price Management'))
                        @can('Read Price')
                            <li>
                                <a name="menuTitles" href="{{ route('prices.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('prices') ? 'is-active' : '' }}">
                                    Pricings
                                </a>
                            </li>
                        @endcan
                    @endif
                </ul>
            </li>
        </ul>
    @endcanany

    @can('Read Tender')
        @if ($enabledFeatures->contains('Tender Management'))
            <ul class="menu-list mb-2">
                <li>
                    <button name="menu-accordion" class="button is-borderless text-green is-size-6-5 ml-0">
                        <span class="icon m-0">
                            <i class="fas fa-project-diagram"></i>
                        </span>
                        <span class="ml-2">
                            Tenders
                        </span>
                    </button>
                </li>
                <li>
                    <ul class="mt-0 ml-5 is-hidden">
                        <li>
                            <a name="menuTitles" href="{{ route('tenders.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('tenders') ? 'is-active' : '' }}">
                                Tenders
                            </a>
                        </li>
                        <li>
                            <a name="menuTitles" href="{{ route('tender-checklist-types.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('tender-checklist-types') ? 'is-active' : '' }}">
                                Checklist Categories
                            </a>
                        </li>
                        <li>
                            <a name="menuTitles" href="{{ route('general-tender-checklists.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('general-tender-checklists') ? 'is-active' : '' }}">
                                Available Checklists
                            </a>
                        </li>
                        <li>
                            <a name="menuTitles" href="{{ route('tender-statuses.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('tender-statuses') ? 'is-active' : '' }}">
                                Available Statuses
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        @endif
    @endcan

    @canany(['Read Purchase', 'Read Supplier'])
        <ul class="menu-list mb-2">
            <li>
                <button name="menu-accordion" class="button is-borderless text-green is-size-6-5 ml-0">
                    <span class="icon m-0">
                        <i class="fas fa-shopping-bag"></i>
                    </span>
                    <span class="ml-2">
                        Purchases & Supplier
                    </span>
                </button>
            </li>
            <li>
                <ul class="mt-0 ml-5 is-hidden">
                    @if ($enabledFeatures->contains('Purchase Management'))
                        @can('Read Purchase')
                            <li>
                                <a name="menuTitles" href="{{ route('purchases.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('purchases') ? 'is-active' : '' }}">
                                    Purchases
                                </a>
                            </li>
                        @endcan
                    @endif
                    @if ($enabledFeatures->contains('Supplier Management'))
                        @can('Read Supplier')
                            <li>
                                <a name="menuTitles" href="{{ route('suppliers.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('suppliers') ? 'is-active' : '' }}">
                                    Suppliers
                                </a>
                            </li>
                        @endcan
                    @endif
                </ul>
            </li>
        </ul>
    @endcanany

    @can('Read Product')
        @if ($enabledFeatures->contains('Product Management'))
            <ul class="menu-list mb-2">
                <li>
                    <button name="menu-accordion" class="button is-borderless text-green is-size-6-5 ml-0">
                        <span class="icon m-0">
                            <i class="fas fa-th"></i>
                        </span>
                        <span class="ml-2">
                            Products & Categories
                        </span>
                    </button>
                </li>
                <li>
                    <ul class="mt-0 ml-5 is-hidden">
                        <li>
                            <a name="menuTitles" href="{{ route('products.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('products') ? 'is-active' : '' }}">
                                Products
                            </a>
                        </li>
                        <li>
                            <a name="menuTitles" href="{{ route('categories.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('categories') ? 'is-active' : '' }}">
                                Categories
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        @endif
    @endcan

    @canany(['Read Employee', 'Update Company'])
        <ul class="menu-list mb-2">
            <li>
                <button name="menu-accordion" class="button is-borderless text-green is-size-6-5 ml-0">
                    <span class="icon m-0">
                        <i class="fas fa-cog"></i>
                    </span>
                    <span class="ml-2">
                        Settings
                    </span>
                </button>
            </li>
            <li>
                <ul class="mt-0 ml-5 is-hidden">
                    @if ($enabledFeatures->contains('User Management'))
                        @can('Read Employee')
                            <li>
                                <a name="menuTitles" href="{{ route('employees.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('employees') ? 'is-active' : '' }}">
                                    Users
                                </a>
                            </li>
                        @endcan
                    @endif
                    @if ($enabledFeatures->contains('General Settings'))
                        @can('Update Company')
                            <li>
                                <a name="menuTitles" href="{{ route('companies.edit', userCompany()->id) }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('companies.edit') ? 'is-active' : '' }}">
                                    Company Profile
                                </a>
                            </li>
                        @endcan
                    @endif
                </ul>
            </li>
        </ul>
    @endcanany
</aside>
