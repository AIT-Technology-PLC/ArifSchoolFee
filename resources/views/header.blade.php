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
                    <figure class="image is-24x24" style="margin: auto !important">
                        <img class="is-rounded" src="{{ asset('img/nabil.jpg') }}">
                    </figure>
                    <h1 class="ml-3 has-text-white-ter has-text-weight-light is-uppercase is-size-5">
                        Onrica Technologies PLC
                    </h1>
                </a>
            </div>
            <div class="navbar-end">
                <a href="#" class="navbar-item has-text-white link-text">
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
                            Nabil Hassen
                        </span>
                        <span class="icon has-text-white is-size-7">
                            <i class="fas fa-angle-down"></i>
                        </span>
                    </a>
                    <div class="navbar-dropdown is-boxed">
                        <a class="navbar-item text-green">
                            <span class="icon is-medium">
                                <i class="fas fa-address-card"></i>
                            </span>
                            <span> 
                                Profile
                            </span>
                        </a>
                        <a class="navbar-item text-green">
                            <span class="icon is-medium">
                                <i class="fas fa-lock-open"></i>
                            </span>
                            <span> 
                                Permissions
                            </span>
                        </a>
                        <hr class="navbar-divider">
                        <a class="navbar-item has-text-danger">
                            <span class="icon is-medium">
                                <i class="fas fa-power-off"></i>
                            </span>
                            <span> 
                                Logout
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
