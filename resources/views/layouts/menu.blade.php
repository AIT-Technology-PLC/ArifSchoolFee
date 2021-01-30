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
        Dashboard
    </p>
    <ul class="menu-list mb-5">
        <li>
            <a name="menuTitles" href="/home" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('home') ? 'is-active' : '' }}">
                <span class="icon">
                    <i class="fas fa-chart-line"></i>
                </span>
                <span>
                    Analytics & Insights
                </span>
            </a>
        </li>
    </ul>

    @can('onlyPremiumOrProfessional', auth()->user()->employee->company)
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
        </ul>
    @endcan

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
    </ul>

    @can('onlyPremiumOrProfessional', auth()->user()->employee->company)
        <p class="menu-label has-text-weight-bold text-green">
            Warehouse Operations
        </p>
        <ul class="menu-list mb-5">
            <li>
                <a name="menuTitles" href="{{ route('gdns.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('gdns') ? 'is-active' : '' }}">
                    <span class="icon">
                        <i class="fas fa-file-invoice"></i>
                    </span>
                    <span>
                        SIV/GDN Management
                    </span>
                </a>
            </li>
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
            <li>
                <ul class="mt-0">
                    <li>
                        <a name="menuTitles" href="{{ route('grns.create') }}" class="has-text-grey has-text-weight-normal is-size-6-5{{ request()->is('grns/create') ? 'is-active' : '' }}">
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
        </ul>
    @endcan

    @can('onlyPremium', auth()->user()->employee->company)
        <p class="menu-label has-text-weight-bold text-green">
            Manufacturing Inventory
        </p>
        <ul class="menu-list mb-5">
            <li>
                <a class="has-text-grey has-text-weight-normal is-size-6-5">
                    <span class="icon">
                        <i class="fas fa-plus-circle"></i>
                    </span>
                    <span>
                        Start New Production
                    </span>
                </a>
            </li>
            <li>
                <a class="has-text-grey has-text-weight-normal is-size-6-5">
                    <span class="icon">
                        <i class="fas fa-boxes"></i>
                    </span>
                    <span>
                        Finished Products
                    </span>
                </a>
            </li>
            <li>
                <a class="has-text-grey has-text-weight-normal is-size-6-5">
                    <span class="icon">
                        <i class="fas fa-sync-alt"></i>
                    </span>
                    <span>
                        In-process Prodcuts
                    </span>
                </a>
            </li>
            <li>
                <a class="has-text-grey has-text-weight-normal is-size-6-5">
                    <span class="icon">
                        <i class="fas fa-box-open"></i>
                    </span>
                    <span>
                        Raw Materials
                    </span>
                </a>
            </li>
            <li>
                <a class="has-text-grey has-text-weight-normal is-size-6-5">
                    <span class="icon">
                        <i class="fas fa-tools"></i>
                    </span>
                    <span>
                        MRO Items
                    </span>
                </a>
            </li>
        </ul>
    @endcan

    <p class="menu-label has-text-weight-bold text-green">
        Merchandise Inventory
    </p>
    <ul class="menu-list mb-5">
        <li>
            <a name="menuTitles" href="{{ route('merchandises.level') }}" class="has-text-grey has-text-weight-normal is-size-6-5  {{ request()->is('merchandises/level') ? 'is-active' : '' }}">
                <span class="icon">
                    <i class="fas fa-chart-bar"></i>
                </span>
                <span>
                    Current Inventory Level
                </span>
            </a>
        </li>
        <li>
            <a name="menuTitles" href="{{ route('merchandises.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5  {{ request()->is('merchandises') ? 'is-active' : '' }}">
                <span class="icon">
                    <i class="fas fa-dolly-flatbed"></i>
                </span>
                <span>
                    Inventory Management
                </span>
            </a>
        </li>
        <li>
            <ul class="mt-0">
                <li>
                    <a name="menuTitles" href="{{ route('merchandises.create') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('merchandises/create') ? 'is-active' : '' }}">
                        <span class="icon">
                            <i class="fas fa-plus-circle"></i>
                        </span>
                        <span>
                            Add New
                        </span>
                    </a>
                </li>
            </ul>
        </li>
    </ul>

    @can('onlyPremiumOrProfessional', auth()->user()->employee->company)
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
        </ul>
    @endcan

    <p class="menu-label has-text-weight-bold text-green">
        Purchases & Suppliers
    </p>
    <ul class="menu-list mb-5">
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
        @can('onlyPremiumOrProfessional', auth()->user()->employee->company)
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
    </ul>

    <p class="menu-label has-text-weight-bold text-green">
        Sales & Customers
    </p>
    <ul class="menu-list mb-5">
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
        @can('onlyPremiumOrProfessional', auth()->user()->employee->company)
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
    </ul>

    @can('viewAny', auth()->user()->employee)
        <p class="menu-label has-text-weight-bold text-green">
            Settings
        </p>
        <ul class="menu-list mb-5">
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
            @can('delete', auth()->user()->employee)
                <li>
                    <a name="menuTitles" href="{{ route('companies.edit', auth()->user()->employee->company_id) }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('companies/' . auth()->user()->employee->company_id . '/edit') ? 'is-active' : '' }}">
                        <span class="icon">
                            <i class="fas fa-cog"></i>
                        </span>
                        <span>
                            General Settings
                        </span>
                    </a>
                </li>
            @endcan
        </ul>
    @endcan
</aside>
