<aside class="menu">
    <ul class="menu-list has-text-centered">
        <li>
            <figure class="image is-64x64" style="margin: auto !important">
                <img class="is-rounded" src="{{ asset('img/nabil.jpg') }}">
            </figure>
            <div class="has-text-weight-bold mt-3 is-capitalized">
                {{ auth()->user()->name }}
            </div>
            <div class="has-text-grey has-text-weight-bold is-size-6-5 is-capitalized">
                {{ auth()->user()->employee->position ?? 'Job: Not Assigned' }}
            </div>
        </li>
    </ul>

    <hr>

    <p class="menu-label has-text-weight-bold text-green">
        Dashboard
    </p>
    <ul class="menu-list mb-5">
        <li>
            <a href="/home" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('home') ? 'is-active' : '' }}">
                <span class="icon">
                    <i class="fas fa-tachometer-alt"></i>
                </span>
                <span>
                    Home
                </span>
            </a>
        </li>
    </ul>

    <p class="menu-label has-text-weight-bold text-green">
        Product Management
    </p>
    <ul class="menu-list mb-5">
        <li>
            <a href="{{ route('products.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('products') ? 'is-active' : '' }}">
                <span class="icon">
                    <i class="fas fa-th"></i>
                </span>
                <span>
                    Products Management
                </span>
            </a>
        </li>
        <li>
            <a href="/products/create" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('products/create') ? 'is-active' : '' }}">
                <span class="icon">
                    <i class="fas fa-plus-circle"></i>
                </span>
                <span>
                    Create New Product
                </span>
            </a>
        </li>
        <li>
            <a href="{{ route('categories.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('categories') ? 'is-active' : '' }}">
                <span class="icon">
                    <i class="fas fa-layer-group"></i>
                </span>
                <span>
                    Categories Management
                </span>
            </a>
        </li>
        <li>
            <a href="{{ route('categories.create') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('categories/create') ? 'is-active' : '' }}">
                <span class="icon">
                    <i class="fas fa-plus-circle"></i>
                </span>
                <span>
                    Create New Category
                </span>
            </a>
        </li>
    </ul>

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

    <p class="menu-label has-text-weight-bold text-green">
        Merchandise Inventory
    </p>
    <ul class="menu-list mb-5">
        <li>
            <a class="has-text-grey has-text-weight-normal is-size-6-5">
                <span class="icon">
                    <i class="fas fa-plus-circle"></i>
                </span>
                <span>
                    Add New Product
                </span>
            </a>
        </li>
        <li>
            <a class="has-text-grey has-text-weight-normal is-size-6-5">
                <span class="icon">
                    <i class="fas fa-boxes"></i>
                </span>
                <span>
                    Products
                </span>
            </a>
        </li>
    </ul>

    <p class="menu-label has-text-weight-bold text-green">
        Purchases
    </p>
    <ul class="menu-list mb-5">
        <li>
            <a href="{{ route('purchases.create') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('purchases/create') ? 'is-active' : '' }}">
                <span class="icon">
                    <i class="fas fa-plus-circle"></i>
                </span>
                <span>
                    Create Purchase Order
                </span>
            </a>
        </li>
        <li>
            <a href="{{ route('purchases.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('purchases') ? 'is-active' : '' }}">
                <span class="icon">
                    <i class="fas fa-shopping-bag"></i>
                </span>
                <span>
                    Purchase Management
                </span>
            </a>
        </li>
        <li>
            <a class="has-text-grey has-text-weight-normal is-size-6-5">
                <span class="icon">
                    <i class="fas fa-credit-card"></i>
                </span>
                <span>
                    Purchase Expenses
                </span>
            </a>
        </li>
        @can('onlyPremiumOrProfessional', auth()->user()->employee->company)
            <li>
                <a href="{{ route('suppliers.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('suppliers') ? 'is-active' : '' }}">
                    <span class="icon">
                        <i class="fas fa-address-card"></i>
                    </span>
                    <span>
                        Supplier Management
                    </span>
                </a>
            </li>
        @endcan
    </ul>

    @can('viewAny', auth()->user()->employee)
        <p class="menu-label has-text-weight-bold text-green">
            Settings
        </p>
        <ul class="menu-list mb-5">
            <li>
                <a href="{{ route('employees.create') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('employees/create') ? 'is-active' : '' }}">
                    <span class="icon">
                        <i class="fas fa-user-plus"></i>
                    </span>
                    <span>
                        Add New Employee
                    </span>
                </a>
            </li>
            <li>
                <a href="{{ route('employees.index') }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('employees') ? 'is-active' : '' }}">
                    <span class="icon">
                        <i class="fas fa-users"></i>
                    </span>
                    <span>
                        Employee Management
                    </span>
                </a>
            </li>
            @can('delete', auth()->user()->employee)
                <li>
                    <a href="{{ route('companies.edit', auth()->user()->employee->company_id) }}" class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('companies/' . auth()->user()->employee->company_id . '/edit') ? 'is-active' : '' }}">
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
