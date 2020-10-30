<nav class="navbar is-fixed-top is-transparent bg-green">
    <div class="container is-fluid">
        <div class="navbar-brand">
            <a class="navbar-item" href="/">
                <img src="{{ asset('img/logo.png') }}" width="120" style="max-height: 70px">
                <span class="has-text-grey-lighter is-size-5">
                    Inventory
                </span>
            </a>
            <a class="navbar-burger burger">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>
        <div class="navbar-menu">
            <div class="navbar-end">
                <a class="navbar-item">
                    <figure class="image is-32x32" style="margin: auto !important">
                        <img class="is-rounded" src="{{ asset('img/nabil.jpg') }}">
                    </figure>
                    <span class="ml-3 has-text-white-ter has-text-weight-medium is-uppercase is-size-4">
                        Onrica Technologies PLC
                    </span>
                </a>
            </div>
            <div class="navbar-end">
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
