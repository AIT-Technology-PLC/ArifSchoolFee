<aside class="menu">
    <ul class="menu-list">
        <li>
            <div class="columns is-marginless is-mobile is-vcentered">
                <div class="column is-3-mobile is-4-tablet is-paddingless">
                    <figure class="image is-48x48 m-auto">
                        <img
                            class="is-rounded"
                            src="{{ asset('img/user.jpg') }}"
                        >
                    </figure>
                </div>
                <div class="column is-paddingless">
                    <div class="has-text-weight-bold has-text-black is-capitalized is-size-7">
                        {{ authUser()->name }}
                    </div>
                </div>
            </div>
            <div class="buttons is-hidden-tablet ml-3 mt-5">
                <form
                    class="is-inline"
                    action="{{ route('logout') }}"
                    method="POST"
                >
                    @csrf
                    <x-common.button
                        tag="button"
                        mode="button"
                        icon="fas fa-power-off"
                        label="Logout"
                        class="btn-purple is-outlined is-small"
                    />
                </form>
                <x-common.dropdown
                    name="More"
                    class="ml-2"
                >
                    <x-common.dropdown-item>
                        <a
                            href="{{ route('password.edit') }}"
                            class="text-green"
                        >
                            <span class="icon is-medium">
                                <i class="fas fa-lock"></i>
                            </span>
                            <span>
                                Change Password
                            </span>
                        </a>
                    </x-common.dropdown-item>
                    <x-common.dropdown-item>
                        @if (isFeatureEnabled('Push Notification'))
                            <x-common.push-notifications />
                        @endif
                    </x-common.dropdown-item>
                </x-common.dropdown>
            </div>
        </li>
    </ul>

    <hr>

    <ul
        x-data="sideMenuAccordion"
        class="menu-list mb-2"
    >
        <li>
            <x-common.button
                tag="a"
                href="{{ route('admin.reports.dashboard') }}"
                class="text-green is-size-6-5 has-text-left"
                ::class="{ 'is-active': isAccordionActive }"
                x-init="{{ request()->routeIs('admin.reports.dashboard') ? 'activateAccordion' : '' }}"
            >
                <x-common.icon
                    name="fas fa-gauge"
                    class="pl-1"
                />
                <span class="ml-1"> Dashboard </span>
            </x-common.button>
        </li>
    </ul>

    <ul
        x-data="sideMenuAccordion"
        class="menu-list mb-2"
    >
        <li>
            <x-common.button
                tag="a"
                href="{{ route('admin.companies.index') }}"
                class="text-green is-size-6-5 has-text-left"
                ::class="{ 'is-active': isAccordionActive }"
                x-init="{{ request()->routeIs('admin.companies.*') ? 'activateAccordion' : '' }}"
            >
                <x-common.icon
                    name="fas fa-bank"
                    class="pl-1"
                />
                <span class="ml-1"> Companies </span>
            </x-common.button>
        </li>
    </ul>

    <ul
        x-data="sideMenuAccordion"
        class="menu-list mb-2"
    >
        <li>
            <x-common.button
                tag="a"
                href="{{ route('admin.reports.subscriptions') }}"
                class="text-green is-size-6-5 has-text-left"
                ::class="{ 'is-active': isAccordionActive }"
                x-init="{{ request()->routeIs('admin.reports.subscriptions') ? 'activateAccordion' : '' }}"
            >
                <x-common.icon
                    name="fas fa-file-contract"
                    class="pl-1"
                />
                <span class="ml-1"> Subscriptions </span>
            </x-common.button>
        </li>
    </ul>

    <ul
        x-data="sideMenuAccordion"
        class="menu-list mb-2"
    >
        <li>
            <x-common.button
                tag="a"
                href="{{ route('admin.reports.transactions') }}"
                class="text-green is-size-6-5 has-text-left"
                ::class="{ 'is-active': isAccordionActive }"
                x-init="{{ request()->routeIs('admin.reports.transactions') ? 'activateAccordion' : '' }}"
            >
                <x-common.icon
                    name="fas fa-folder-open"
                    class="pl-1"
                />
                <span class="ml-1"> Transactions </span>
            </x-common.button>
        </li>
    </ul>

    @can('Manage Admin Panel Users')
        <ul
            x-data="sideMenuAccordion"
            class="menu-list mb-2"
        >
            <li>
                <x-common.button
                    tag="a"
                    href="{{ route('admin.users.index') }}"
                    class="text-green is-size-6-5 has-text-left"
                    ::class="{ 'is-active': isAccordionActive }"
                    x-init="{{ request()->routeIs('admin.users.*') ? 'activateAccordion' : '' }}"
                >
                    <x-common.icon
                        name="fas fa-user-group"
                        class="pl-1"
                    />
                    <span class="ml-1"> Admins </span>
                </x-common.button>
            </li>
        </ul>
    @endcan
</aside>
