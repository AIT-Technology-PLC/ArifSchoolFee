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
                    <div class="has-text-grey is-size-7 is-capitalized">
                       System Admin
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
                            class="text-blue"
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
                class="text-blue is-size-6-5 has-text-left"
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
                class="text-blue is-size-6-5 has-text-left"
                ::class="{ 'is-active': isAccordionActive }"
                x-init="{{ request()->routeIs('admin.companies.*') ? 'activateAccordion' : '' }}"
            >
                <x-common.icon
                    name="fas fa-shop"
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
                tag="button"
                mode="button"
                class="is-fullwidth is-justify-content-left is-borderless text-blue is-size-6-5 ml-0"
                ::class="{ 'is-active': isAccordionActive }"
                @click="toggleAccordion"
            >
                <x-common.icon
                    name="fas fa-bars"
                    class="m-0"
                />
                <span class="ml-2"> Resource Management </span>
                <span class="icon ml-auto">
                    <i
                        class="fas fa-caret-right"
                        :class="{ 'fa-caret-right': !isAccordionOpen, 'fa-caret-down': isAccordionOpen }"
                    ></i>
                </span>
            </x-common.button>
        </li>
        <li>
            <ul
                class="mt-0 ml-5"
                x-cloak
                x-show="isAccordionOpen"
                x-collapse
            >
                <li>
                    <x-common.button
                        tag="a"
                        href="{{ route('admin.plans.index') }}"
                        label="Plans"
                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('admin.plans.*') ? 'text-blue has-text-weight-bold' : '' }}"
                        x-init="{{ request()->routeIs('admin.plans.*') ? 'activateAccordion' : '' }}"
                    />
                </li>
                <li>
                    <x-common.button
                        tag="a"
                        href="{{ route('admin.features.index') }}"
                        label="Features"
                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('admin.features.*') ? 'text-blue has-text-weight-bold' : '' }}"
                        x-init="{{ request()->routeIs('admin.features.*') ? 'activateAccordion' : '' }}"
                    />
                </li>
                <li>
                    <x-common.button
                        tag="a"
                        href="{{ route('admin.limits.index') }}"
                        label="Resource"
                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('admin.limits.*') ? 'text-blue has-text-weight-bold' : '' }}"
                        x-init="{{ request()->routeIs('admin.limits.*') ? 'activateAccordion' : '' }}"
                    />
                </li>
            </ul>
        </li>
    </ul>

    <ul
        x-data="sideMenuAccordion"
        class="menu-list mb-2"
    >
        <li>
            <x-common.button
                tag="button"
                mode="button"
                class="is-fullwidth is-justify-content-left is-borderless text-blue is-size-6-5 ml-0"
                ::class="{ 'is-active': isAccordionActive }"
                @click="toggleAccordion"
            >
                <x-common.icon
                    name="fas fa-chart-pie"
                    class="m-0"
                />
                <span class="ml-2"> Report and Analytics </span>
                <span class="icon ml-auto">
                    <i
                        class="fas fa-caret-right"
                        :class="{ 'fa-caret-right': !isAccordionOpen, 'fa-caret-down': isAccordionOpen }"
                    ></i>
                </span>
            </x-common.button>
        </li>
        <li>
            <ul
                class="mt-0 ml-5"
                x-cloak
                x-show="isAccordionOpen"
                x-collapse
            >
                <li>
                    <x-common.button
                        tag="a"
                        href="{{ route('admin.reports.users') }}"
                        label="Users"
                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('admin.reports.user') ? 'text-blue has-text-weight-bold' : '' }}"
                        x-init="{{ request()->routeIs('admin.reports.user') ? 'activateAccordion' : '' }}"
                    />
                </li>
                <li>
                    <x-common.button
                        tag="a"
                        href="{{ route('admin.reports.transactions') }}"
                        label="Transactions"
                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('admin.reports.transactions') ? 'text-blue has-text-weight-bold' : '' }}"
                        x-init="{{ request()->routeIs('admin.reports.transactions') ? 'activateAccordion' : '' }}"
                    />
                </li>
                <li>
                    <x-common.button
                        tag="a"
                        href="{{ route('admin.reports.subscriptions') }}"
                        label="Subscriptions"
                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('admin.reports.subscriptions') ? 'text-blue has-text-weight-bold' : '' }}"
                        x-init="{{ request()->routeIs('admin.reports.subscriptions') ? 'activateAccordion' : '' }}"
                    />
                </li>
            </ul>
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
                    class="text-blue is-size-6-5 has-text-left"
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
