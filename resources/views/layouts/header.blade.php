<nav class="navbar is-fixed-top is-transparent bg-green">
    <div class="container is-fluid p-lr-0">
        <div class="navbar-brand">
            <a
                class="navbar-item is-hidden-touch pl-0"
                href="/"
            >
                <img
                    src="{{ asset('img/logo.webp') }}"
                    width="120"
                    style="max-height: 70px"
                >
                <span class="has-text-white has-text-weight-light is-size-4 mb-1">
                    Smart<span class="has-text-weight-bold">Work&trade;</span>
                </span>
            </a>
            <a
                class="navbar-item is-hidden-desktop"
                href="#"
            >
                <span class="has-text-white has-text-weight-light is-size-4">
                    Smart<span class="has-text-weight-bold">Work&trade;</span>
                </span>
            </a>
            <a
                x-data
                @click="$dispatch('open-create-modal')"
                class="navbar-item has-text-white is-size-5 is-hidden-desktop to-the-right"
                data-title="Create New ..."
            >
                <span class="icon">
                    <i class="fas fa-plus"></i>
                </span>
            </a>
            <livewire:notification-counter class="navbar-item has-text-white is-size-5 is-hidden-desktop" />
            <span
                x-data="toggler"
                @click="toggle;$dispatch('side-menu-opened')"
                class="navbar-item has-text-white is-size-5 is-hidden-desktop"
            >
                <span class="icon">
                    <i
                        class="fas fa-bars"
                        :class="{ 'fa-times': !isHidden }"
                    ></i>
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
                            {{ userCompany()->name }}
                        </span>
                    </h1>
                </a>
            </div>
            <div class="navbar-end">
                <a
                    href="/"
                    id="mainMenuButton"
                    class="navbar-item has-text-white link-text"
                    data-title="Main Menu"
                >
                    <span class="icon">
                        <i class="fas fa-home"></i>
                    </span>
                </a>
                <a
                    x-data
                    class="navbar-item has-text-white link-text"
                    data-title="Back"
                    @click="history.back()"
                >
                    <span class="icon">
                        <i class="fas fa-arrow-left"></i>
                    </span>
                </a>
                <a
                    x-data
                    class="navbar-item has-text-white link-text"
                    data-title="Forward"
                    @click="history.forward()"
                >
                    <span class="icon">
                        <i class="fas fa-arrow-right"></i>
                    </span>
                </a>
                <a
                    x-data
                    class="navbar-item has-text-white link-text"
                    data-title="Refresh"
                    @click="location.reload()"
                >
                    <span class="icon">
                        <i class="fas fa-redo-alt"></i>
                    </span>
                </a>
                <a
                    x-data
                    @click="$dispatch('open-create-modal')"
                    class="navbar-item has-text-white link-text"
                    data-title="Create New ..."
                >
                    <span class="icon">
                        <i class="fas fa-plus"></i>
                    </span>
                </a>
                <livewire:notification-counter class="navbar-item has-text-white link-text" />
                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link is-arrowless">
                        <figure
                            class="image is-24x24"
                            style="margin: auto !important"
                        >
                            <img
                                class="is-rounded"
                                src="{{ asset('img/user.jpg') }}"
                            >
                        </figure>
                        <span class="ml-3 has-text-white is-size-7 has-text-weight-medium">
                            {{ auth()->user()->name }}
                        </span>
                        <span class="icon has-text-white is-size-7">
                            <i class="fas fa-angle-down"></i>
                        </span>
                    </a>
                    <div
                        class="navbar-dropdown is-boxed"
                        style="left: -68px !important"
                    >
                        <a
                            href="{{ route('employees.show', auth()->user()->employee->id) }}"
                            class="navbar-item text-green"
                        >
                            <span class="icon is-medium">
                                <i class="fas fa-address-card"></i>
                            </span>
                            <span>
                                My Profile
                            </span>
                        </a>
                        <hr class="navbar-divider">
                        <a
                            href="{{ route('password.edit') }}"
                            class="navbar-item text-green"
                        >
                            <span class="icon is-medium">
                                <i class="fas fa-lock"></i>
                            </span>
                            <span>
                                Change Password
                            </span>
                        </a>
                        <hr class="navbar-divider">
                        <form
                            class="navbar-item"
                            action="{{ route('logout') }}"
                            method="POST"
                        >
                            @csrf
                            <x-common.icon
                                name="fas fa-power-off"
                                class="is-medium text-purple"
                            />
                            <span>
                                <x-common.button
                                    tag="button"
                                    mode="button"
                                    label="Logout"
                                    class="text-purple is-borderless is-size-6-5 ml-0 pl-0"
                                >
                            </span>
                            </x-common.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <x-common.create-menu />
        <livewire:notifications />
    </div>
</nav>
