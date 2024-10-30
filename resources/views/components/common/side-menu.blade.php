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
                        {{ authUser()->employee->position ?? 'Job: Not Assigned' }}
                    </div>
                    @if (userCompany()->isSubscriptionNearExpiry())
                        <div class="tag bg-lightpurple text-purple is-size-7 has-text-weight-bold is-capitalized mt-3">
                            <span class="icon">
                                <i class="fas fa-exclamation-circle"></i>
                            </span>
                            <span>
                                DAYS LEFT: {{ today()->diffInDays(userCompany()->subscription_expires_on, false) }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
            <div class="buttons is-hidden-desktop ml-3 mt-5">
                <x-common.button
                    tag="a"
                    mode="button"
                    href="{{ route('employees.show', authUser()->employee->id) }}"
                    icon="fas fa-address-card"
                    label="My Profile"
                    class="bg-green has-text-white is-small"
                />
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
                href="/"
                class="text-blue is-size-6-5 has-text-left"
                ::class="{ 'is-active': isAccordionActive }"
                x-init="{{ request()->routeIs('home') ? 'activateAccordion' : '' }}"
            >
                <x-common.icon
                    name="fas fa-bars"
                    class="pl-1"
                />
                <span class="ml-1"> Dashboard </span>
            </x-common.button>
        </li>
    </ul>
    
    @if (isFeatureEnabled('User Management', 'General Settings'))
        @canany(['Update Company'])
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
                            name="fas fa-cog"
                            class="m-0"
                        />
                        <span class="ml-2"> Settings </span>
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
                        @if (isFeatureEnabled('User Management') && !isFeatureEnabled('Employee Management'))
                            @can('Read Employee')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('employees.index') }}"
                                        label="Employees"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('employees.*') ? 'text-blue has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('employees.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                        @if (isFeatureEnabled('General Settings'))
                            @can('Update Company')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('companies.edit', userCompany()->id) }}"
                                        label="Company Profile"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('companies.edit') ? 'text-blue has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('companies.edit') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                    </ul>
                </li>
            </ul>
        @endcanany
    @endif
</aside>
