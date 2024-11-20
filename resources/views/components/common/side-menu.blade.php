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
                    href="{{ route('users.show', authUser()->employee->id) }}"
                    icon="fas fa-address-card"
                    label="My Profile"
                    class="bg-softblue has-text-white is-small"
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
                    name="fas fa-dashboard"
                    class="pl-1"
                />
                <span class="ml-1"> Dashboard </span>
            </x-common.button>
        </li>
    </ul>

    @if (isFeatureEnabled('Branch Management'))
        @canany(['Read Branch'])
            <ul
                x-data="sideMenuAccordion"
                class="menu-list mb-2"
            >
                <li>
                    <x-common.button
                        tag="a"
                        href="{{ route('branches.index') }}"
                        class="text-blue is-size-6-5 has-text-left"
                        ::class="{ 'is-active': isAccordionActive }"
                        x-init="{{ request()->routeIs('branches.*') ? 'activateAccordion' : '' }}"
                    >
                        <x-common.icon
                            name="fas fa-code-branch"
                            class="pl-1"
                        />
                        <span class="ml-1"> Branch Management</span>
                    </x-common.button>
                </li>
            </ul>
        @endcanany
    @endif

    @if (isFeatureEnabled('Academic Year','Section Management', 'Class Management'))
        @canany(['Read Academic Year','Read Section','Read Class'])
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
                            name="fas fa-sitemap"
                            class="m-0"
                        />
                        <span class="ml-2"> Academic Master </span>
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
                        @if (isFeatureEnabled('Academic Year'))
                                @can('Read Academic Year')
                                    <li>
                                        <x-common.button
                                            tag="a"
                                            href="{{ route('academic-years.index') }}"
                                            label="Academic Year"
                                            class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('academic-years.*') ? 'text-blue has-text-weight-bold' : '' }}"
                                            x-init="{{ request()->routeIs('academic-years.*') ? 'activateAccordion' : '' }}"
                                        />
                                    </li>
                                @endcan
                        @endif
                        @if (isFeatureEnabled('Section Management'))
                            @can('Read Section')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('sections.index') }}"
                                        label="Section"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('sections.*') ? 'text-blue has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('sections.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                        @if (isFeatureEnabled('Class Management'))
                            @can('Read Class')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('school-classes.index') }}"
                                        label="Class"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('school-classes.*') ? 'text-blue has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('school-classes.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                    </ul>
                </li>
            </ul>
        @endcanany
    @endif

    @if (isFeatureEnabled('Route Management', 'Vehicle Management'))
        @canany(['Read Route','Read Vehicle'])
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
                            name="fas fa-route"
                            class="m-0"
                        />
                        <span class="ml-2"> Transport </span>
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
                        @if (isFeatureEnabled('Vehicle Management'))
                            @can('Read Vehicle')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('vehicles.index') }}"
                                        label="Vehicle"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('vehicles.*') ? 'text-blue has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('vehicles.*') ? 'activateAccordion' : '' }}"
                                    />
                                    </li>
                                @endcan
                        @endif
                        @if (isFeatureEnabled('Route Management'))
                            @can('Read Route')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('routes.index') }}"
                                        label="Route"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('routes.*') ? 'text-blue has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('routes.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                    </ul>
                </li>
            </ul>
        @endcanany
    @endif

    @if (isFeatureEnabled('Student Category','Student Group','Student Management'))
        @canany(['Read Student Category','Read Student Group','Read Student'])
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
                            name="fas fa-graduation-cap"
                            class="m-0"
                        />
                        <span class="ml-2"> Student Master  </span>

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
                        x-show="isAccordionOpen"
                        x-cloak
                        x-collapse
                    >
                    @if (isFeatureEnabled('Student Category'))
                        @can('Read Student Category')
                            <li>
                                <x-common.button
                                    tag="a"
                                    href="{{ route('student-categories.index') }}"
                                    label="Student Category"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('student-categories.*') ? 'text-blue has-text-weight-bold' : '' }}"
                                    x-init="{{ request()->routeIs('student-categories.*') ? 'activateAccordion' : '' }}"
                                />
                            </li>
                        @endcan
                    @endif
                    @if (isFeatureEnabled('Student Group'))
                        @can('Read Student Group')
                            <li>
                                <x-common.button
                                    tag="a"
                                    href="{{ route('student-groups.index') }}"
                                    label="Student Group"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('student-groups.*') ? 'text-blue has-text-weight-bold' : '' }}"
                                    x-init="{{ request()->routeIs('student-groups.*') ? 'activateAccordion' : '' }}"
                                />
                            </li>
                        @endcan
                    @endif
                    @if (isFeatureEnabled('Student Management'))
                        @can('Read Student')
                            <li>
                                <x-common.button
                                    tag="a"
                                    href="{{ route('students.index') }}"
                                    label="Student Directory"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('students.*') ? 'text-blue has-text-weight-bold' : '' }}"
                                    x-init="{{ request()->routeIs('students.*') ? 'activateAccordion' : '' }}"
                                />
                            </li>
                        @endcan
                    @endif
                    </ul>
                </li>
            </ul>
        @endcan
    @endif

    @if (isFeatureEnabled('Designation Management','Department Management', 'Staff Management'))
        @canany(['Read Designation','Read Department','Read Staff'])
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
                            name="fas fa-users"
                            class="m-0"
                        />
                        <span class="ml-2"> Employee Master </span>
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
                        @if (isFeatureEnabled('Designation Management'))
                                @can('Read Designation')
                                    <li>
                                        <x-common.button
                                            tag="a"
                                            href="{{ route('designations.index') }}"
                                            label="Designation"
                                            class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('designations.*') ? 'text-blue has-text-weight-bold' : '' }}"
                                            x-init="{{ request()->routeIs('designations.*') ? 'activateAccordion' : '' }}"
                                        />
                                    </li>
                                @endcan
                        @endif
                        @if (isFeatureEnabled('Department Management'))
                            @can('Read Department')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('departments.index') }}"
                                        label="Department"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('departments.*') ? 'text-blue has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('departments.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                        @if (isFeatureEnabled('Staff Management'))
                            @can('Read Staff')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('staff.index') }}"
                                        label="Staff Directory"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('staffs.*') ? 'text-blue has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('staffs.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                    </ul>
                </li>
            </ul>
        @endcanany
    @endif

    @if (isFeatureEnabled('Fee Group','Fee Type', 'Fee Discount','Fee Master', 'Collect Fee'))
        @canany(['Read Fee Group', 'Read Fee Type','Read Fee Discount','Read Fee Master', 'Read Collect Fee', 'Search Fee Payment', 'Search Fee Due'])
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
                            name="fas fa-coins"
                            class="m-0"
                        />
                        <span class="ml-2"> Fee Collection  </span>

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
                        x-show="isAccordionOpen"
                        x-cloak
                        x-collapse
                    >
                    @if (isFeatureEnabled('Fee Group'))
                        @can('Read Fee Group')
                            <li>
                                <x-common.button
                                    tag="a"
                                    href="{{ route('fee-groups.index') }}"
                                    label="Fee Group"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('fee-groups.*') ? 'text-blue has-text-weight-bold' : '' }}"
                                    x-init="{{ request()->routeIs('fee-groups.*') ? 'activateAccordion' : '' }}"
                                />
                            </li>
                        @endcan
                    @endif
                    @if (isFeatureEnabled('Fee Type'))
                        @can('Read Fee Type')
                            <li>
                                <x-common.button
                                    tag="a"
                                    href="{{ route('fee-types.index') }}"
                                    label="Fee Type"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('fee-types.*') ? 'text-blue has-text-weight-bold' : '' }}"
                                    x-init="{{ request()->routeIs('fee-types.*') ? 'activateAccordion' : '' }}"
                                />
                            </li>
                        @endcan
                    @endif
                    @if (isFeatureEnabled('Fee Discount'))
                        @can('Read Fee Discount')
                            <li>
                                <x-common.button
                                    tag="a"
                                    href="{{ route('fee-discounts.index') }}"
                                    label="Fee Discount"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('fee-discounts.*') ? 'text-blue has-text-weight-bold' : '' }}"
                                    x-init="{{ request()->routeIs('fee-discounts.*') ? 'activateAccordion' : '' }}"
                                />
                            </li>
                        @endcan
                    @endif
                    @if (isFeatureEnabled('Fee Master'))
                        @can('Read Fee Master')
                            <li>
                                <x-common.button
                                    tag="a"
                                    href="{{ route('fee-masters.index') }}"
                                    label="Fee Master"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('fee-masters.*') ? 'text-blue has-text-weight-bold' : '' }}"
                                    x-init="{{ request()->routeIs('fee-masters.*') ? 'activateAccordion' : '' }}"
                                />
                            </li>
                        @endcan
                    @endif
                    @if (isFeatureEnabled('Collect Fee'))
                        @can('Read Collect Fee')
                            <li>
                                <x-common.button
                                    tag="a"
                                    href="{{ route('collect-fees.index') }}"
                                    label="Collect Fees"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('collect-fees.*') ? 'text-blue has-text-weight-bold' : '' }}"
                                    x-init="{{ request()->routeIs('collect-fees.*') ? 'activateAccordion' : '' }}"
                                />
                            </li>
                        @endcan
                    @endif
                    @can('Search Fee Payment')
                        <li>
                            <x-common.button
                                tag="a"
                                href="{{ route('search-fee-payments.index') }}"
                                label="Search Fees Payment"
                                class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('search-fee-payments.*') ? 'text-blue has-text-weight-bold' : '' }}"
                                x-init="{{ request()->routeIs('search-fee-payments.*') ? 'activateAccordion' : '' }}"
                            />
                        </li>
                    @endcan
                    @can('Search Fee Due')
                        <li>
                            <x-common.button
                                tag="a"
                                href="{{ route('search-fee-dues.index') }}"
                                label="Search Fees Due"
                                class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('search-fee-dues.*') ? 'text-blue has-text-weight-bold' : '' }}"
                                x-init="{{ request()->routeIs('search-fee-dues.*') ? 'activateAccordion' : '' }}"
                            />
                        </li>
                    @endcan
                    </ul>
                </li>
            </ul>
        @endcan
    @endif

    @if (isFeatureEnabled('Log Management'))
        @canany(['Read User Login Log', 'Read Activity Log'])
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
                            name="fas fa-history"
                            class="m-0"
                        />
                        <span class="ml-2"> Logs </span>
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
                            @can('Read User Login Log')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('user-logs.index') }}"
                                        label="User Log"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('user-logs.*') ? 'text-blue has-text-weight-bold' : '' }} "
                                        x-init="{{ request()->routeIs('user-logs.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                            @can('Read Activity Log')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('activity-logs.index') }}"
                                        label="Activity Log"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('activity-logs.*') ? 'text-blue has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('activity-logs.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                    </ul>
                </li>
            </ul>
        @endcanany
    @endif

    @if (isFeatureEnabled('User Management'))
        @canany(['Read Employee','Update Employee'])
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
                            name="fas fa-user-tie"
                            class="m-0"
                        />
                        <span class="ml-2"> User Management </span>
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
                        @if (isFeatureEnabled('User Management') && isFeatureEnabled('Employee Management'))
                            @can('Read Employee')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('users.index') }}"
                                        label="User Account"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('users.*') ? 'text-blue has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('users.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                            @can('Update Employee')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('login-permissions.index') }}"
                                        label="Login Permission"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('login-permissions.*') ? 'text-blue has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('login-permissions.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                    </ul>
                </li>
            </ul>
        @endcanany
    @endif

    @if (isFeatureEnabled('General Settings'))
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
                        @if (isFeatureEnabled('General Settings'))
                            @can('Update Company')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('schools.edit', userCompany()->id) }}"
                                        label="School Profile"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('schools.edit') ? 'text-blue has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('schools.edit') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('notification-settings.edit', userCompany()->id) }}"
                                        label="Notification and Alert"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('notification-settings.edit') ? 'text-blue has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('notification-settings.edit') ? 'activateAccordion' : '' }}"
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
