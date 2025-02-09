<nav class="navbar is-fixed-top is-transparent bg-softblue">
    <div class="container is-fluid p-lr-0">
        <div class="navbar-brand ml-3">
            <figure class="image is-75x75">
                <img
                    src="{{ asset('img/AIT LOGO WHITE.png') }}"
                >
            </figure>
            <livewire:notification-counter class="navbar-item has-text-white is-size-5 is-hidden-desktop {{ authUser() ? 'to-the-right' : 'to-the-right' }}" />
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
                    <h1 class="ml-5 has-text-white-ter has-text-weight-normal is-uppercase is-size-5">
                        <span class="icon is-medium has-text-white">
                            <i class="fas 
                                {{ 
                                    authUser()->isAdmin() ? 'fa-user-shield' : 
                                    (authUser()->isCallCenter() ? 'fa-headset' : 
                                    (authUser()->isBank() ? 'fa-bank' : 'fa-graduation-cap')) 
                                }}">
                            </i>
                        </span>
                        <span class="is-capitalized">
                            {{ authUser()->isAdmin() ? 'Admin Panel' : (authUser()->isCallCenter() ? 'Call Center' :  (authUser()->isBank() ? authUser()->bank_name : userCompany()->name)) }}
                        </span>
                    </h1>
                </a>
            </div>
            <div class="navbar-end">
                <a
                    x-data="toggler(false)"
                    x-on:click="toggle;$dispatch('toggle-side-menu-on-laptop')"
                    class="navbar-item has-text-white link-text"
                    x-bind:data-title="isHidden ? 'Change to Standard Mode' : 'Change to Fullscreen Mode'"
                >
                    <span class="icon">
                        <i
                            class="fas fa-toggle-on"
                            x-bind:class="{ 'fas fa-toggle-off': isHidden, 'fas fa-toggle-on': !isHidden }"
                        ></i>
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
                            {{ authUser()->name }}
                        </span>
                        <span class="icon has-text-white is-size-7">
                            <i class="fas fa-angle-down"></i>
                        </span>
                    </a>
                    <div
                        class="navbar-dropdown is-boxed"
                        style="left: -68px !important"
                    >
                        @if (!authUser()->isAdmin() && !authUser()->isCallCenter() && !authUser()->isBank())
                            <a
                                href="{{ route('users.show', authUser()->employee->id) }}"
                                class="navbar-item text-blue"
                            >
                                <span class="icon is-medium">
                                    <i class="fas fa-address-card"></i>
                                </span>
                                <span>
                                    My Profile
                                </span>
                            </a>
                            <hr class="navbar-divider">
                        @endif
                        <a
                            href="{{ route('password.edit') }}"
                            class="navbar-item text-blue"
                        >
                            <span class="icon is-medium">
                                <i class="fas fa-lock"></i>
                            </span>
                            <span>
                                Change Password
                            </span>
                        </a>
                        @if (isFeatureEnabled('Push Notification'))
                            <x-common.push-notifications can-update-subscription />
                        @endif
                        <hr class="navbar-divider">
                        <form
                            class="navbar-item"
                            action="{{ authUser()->isAdmin() ? route('admin.logout') : (authUser()->isCallCenter() ? route('callcenter.logout') : (authUser()->isBank() ? route('bank.logout') : route('logout'))) }}"
                            method="POST"
                        >
                            @csrf
                            <x-common.icon
                                name="fas fa-power-off"
                                class="is-medium text-blue"
                            />
                            <span>
                                <x-common.button
                                    tag="button"
                                    mode="button"
                                    label="Logout"
                                    class="text-purpbluele is-borderless is-size-6-5 ml-0 pl-0"
                                >
                            </span>
                            </x-common.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <livewire:notifications />
    </div>
</nav>
