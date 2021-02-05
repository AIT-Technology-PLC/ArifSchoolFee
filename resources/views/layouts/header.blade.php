<nav class="navbar is-fixed-top is-transparent bg-green">
    <div class="container is-fluid p-lr-0">
        <div class="navbar-brand">
            <a class="navbar-item is-hidden-touch" href="/">
                <img src="{{ asset('img/logo.png') }}" width="120" style="max-height: 70px">
                <span class="has-text-white has-text-weight-light is-size-4 mb-1">
                    SmartWork
                </span>
            </a>
            <a class="navbar-item is-hidden-desktop" href="#">
                <img src="{{ asset('img/logo.png') }}" style="width: 80px;max-height: 37px">
                <span class="has-text-white is-size-6">
                    SmartWork
                </span>
            </a>
            <span id="burger-menu" class="navbar-item to-the-right has-text-white is-size-5 is-hidden-desktop">
                <span class="icon">
                    <i id="burgerMenuBars" class="fas fa-bars"></i>
                </span>
            </span>
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
                <a href="/" id="mainMenuButton" class="navbar-item has-text-white link-text" data-title="Main Menu">
                    <span class="icon">
                        <i class="fas fa-bars"></i>
                    </span>
                </a>
                <a id="backButton" class="navbar-item has-text-white link-text" data-title="Back">
                    <span class="icon">
                        <i class="fas fa-arrow-left"></i>
                    </span>
                </a>
                <a id="forwardButton" class="navbar-item has-text-white link-text" data-title="Forward">
                    <span class="icon">
                        <i class="fas fa-arrow-right"></i>
                    </span>
                </a>
                <a id="refreshButton" class="navbar-item has-text-white link-text" data-title="Refresh">
                    <span class="icon">
                        <i class="fas fa-redo-alt"></i>
                    </span>
                </a>
                <a id="createMenuButton" class="navbar-item has-text-white link-text" data-title="Add new product, supplier, purchase ...">
                    <span class="icon">
                        <i class="fas fa-plus"></i>
                    </span>
                </a>
                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link is-arrowless">
                        <figure class="image is-24x24" style="margin: auto !important">
                            <img class="is-rounded" src="{{ asset('img/user.jpg') }}">
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
        @include('layouts.create_menu')
    </div>
</nav>
