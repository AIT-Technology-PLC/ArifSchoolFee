<nav class="navbar is-fixed-top is-transparent bg-green">
    <div class="container is-fluid p-lr-0">
        <div class="navbar-brand">
            <a class="navbar-item is-hidden-touch" href="/">
                <img src="{{ asset('img/logo.png') }}" width="120" style="max-height: 70px">
                <span class="has-text-grey-lighter has-text-weight-light is-size-4 mb-1">
                    Inventory
                </span>
            </a>
            <a class="navbar-item is-hidden-desktop" href="#">
                <img src="{{ asset('img/logo.png') }}" style="max-height: 40px">
                <span class="has-text-grey-lighter is-size-6">
                    Inventories
                </span>
            </a>
            <a id="burger-menu" class="navbar-item to-the-right has-text-white is-size-5 is-hidden-desktop">
                <span class="icon">
                    <i class="fas fa-bars"></i>
                </span>
            </a>
        </div>
        <div class="navbar-menu">
            <div class="navbar-end">
                <a class="navbar-item">
                    <h1 class="ml-3 has-text-white-ter has-text-weight-light is-uppercase is-size-5">
                        <span class="icon is-medium has-text-white">
                            <i class="fas fa-building"></i>
                        </span>
                        <span class="is-capitalized">
                            {{ auth()->user()->employee->company->name }}
                        </span>
                    </h1>
                </a>
            </div>
            <div class="navbar-end">
                <a id="createMenuButton" class="navbar-item has-text-white link-text">
                    <span class="icon">
                        <i class="fas fa-plus"></i>
                    </span>
                </a>
                <a href="#" class="navbar-item has-text-white link-text">
                    <span class="icon">
                        <i class="fas fa-question-circle"></i>
                    </span>
                </a>
                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link is-arrowless">
                        <figure class="image is-24x24" style="margin: auto !important">
                            <img class="is-rounded" src="{{ asset('img/nabil.jpg') }}">
                        </figure>
                        <span class="ml-3 has-text-white is-size-7 has-text-weight-medium">
                            {{ auth()->user()->name }}
                        </span>
                        <span class="icon has-text-white is-size-7">
                            <i class="fas fa-angle-down"></i>
                        </span>
                    </a>
                    <div class="navbar-dropdown is-boxed">
                        <a href="{{ route('employees.show', auth()->user()->employee->id) }}" class="navbar-item text-green">
                            <span class="icon is-medium">
                                <i class="fas fa-address-card"></i>
                            </span>
                            <span>
                                My Profile
                            </span>
                        </a>
                        <hr class="navbar-divider">
                        <a class="navbar-item has-text-danger" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            <span class="icon is-medium">
                                <i class="fas fa-power-off"></i>
                            </span>
                            <span>
                                Logout
                            </span>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="createMenu" class="box is-hidden" style="position: absolute;top:100%;left:70%;width:300px;">
            <h1 class="is-size-7 has-text-centered is-uppercase has-text-weight-medium mb-2">
                <span class="icon">
                    <i class="fas fa-plus-circle"></i>
                </span>
                <span>
                    Create New
                </span>
            </h1>
            <div class="columns is-marginless is-multiline">
                <div class="column is-4 has-text-centered text-green">
                    <a href="{{ route('products.create') }}" class="button is-rounded text-green" title="Create New Product">
                        <span class="icon">
                            <i class="fas fa-boxes"></i>
                        </span>
                    </a>
                    <span class="is-size-7">
                        Product
                    </span>
                </div>
                <div class="column is-4 has-text-centered text-purple">
                    <a href="{{ route('suppliers.create') }}" class="button is-rounded text-purple" title="Create New Supplier">
                        <span class="icon">
                            <i class="fas fa-address-card"></i>
                        </span>
                    </a>
                    <span class="is-size-7">
                        Supplier
                    </span>
                </div>
                <div class="column is-4 has-text-centered text-gold">
                    <a href="{{ route('categories.create') }}" class="button is-rounded text-gold" title="Create New Category">
                        <span class="icon">
                            <i class="fas fa-layer-group"></i>
                        </span>
                    </a>
                    <span class="is-size-7">
                        Category
                    </span>
                </div>
                <div class="column is-4 has-text-centered text-blue">
                    <a href="{{ route('purchases.create') }}" class="button is-rounded text-blue" title="Create New Purchase">
                        <span class="icon">
                            <i class="fas fa-shopping-bag"></i>
                        </span>
                    </a>
                    <span class="is-size-7">
                        Purchase
                    </span>
                </div>
                <div class="column is-4 has-text-centered text-purple">
                    <a href="{{ route('employees.create') }}" class="button is-rounded text-purple" title="Create New Employee">
                        <span class="icon">
                            <i class="fas fa-user-tie"></i>
                        </span>
                    </a>
                    <span class="is-size-7">
                        Employee
                    </span>
                </div>
            </div>
        </div>
    </div>
</nav>
