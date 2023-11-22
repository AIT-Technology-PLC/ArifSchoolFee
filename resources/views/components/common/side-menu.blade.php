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
                        <div class="text-purple is-size-7 has-text-weight-bold is-capitalized mt-3">
                            DAYS LEFT: {{ today()->diffInDays(userCompany()->subscription_expires_on, false) }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="buttons is-hidden-tablet ml-3 mt-5">
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
                    @if (isFeatureEnabled('Leave Management'))
                        <x-common.dropdown-item>
                            <hr class="navbar-divider">
                            <a
                                href="{{ route('leaves.request.create') }}"
                                class="navbar-item text-green"
                            >
                                <span class="icon is-medium">
                                    <i class="fa-solid fa-umbrella-beach"></i>
                                </span>
                                <span>
                                    Request Leave
                                </span>
                            </a>
                        </x-common.dropdown-item>
                    @endif
                    @if (isFeatureEnabled('Expense Claim'))
                        <x-common.dropdown-item>
                            <hr class="navbar-divider">
                            <a
                                href="{{ route('expense-claims.request.create') }}"
                                class="navbar-item text-green"
                            >
                                <span class="icon is-medium">
                                    <i class="fa-solid fa-file-invoice-dollar"></i>
                                </span>
                                <span>
                                    Request Expense Claim
                                </span>
                            </a>
                        </x-common.dropdown-item>
                    @endif
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
                href="/"
                class="text-green is-size-6-5 has-text-left"
                ::class="{ 'is-active': isAccordionActive }"
                x-init="{{ request()->routeIs('home') ? 'activateAccordion' : '' }}"
            >
                <x-common.icon
                    name="fas fa-bars"
                    class="pl-1"
                />
                <span class="ml-1"> Main Menu </span>
            </x-common.button>
        </li>
    </ul>
    @if (isFeatureEnabled('Bill Of Material Management', 'Job Management'))
        @canany(['Read BOM', 'Read Job'])
            <ul
                x-data="sideMenuAccordion"
                class="menu-list mb-2"
            >
                <li>
                    <x-common.button
                        tag="button"
                        mode="button"
                        class="is-fullwidth is-justify-content-left is-borderless text-green is-size-6-5 ml-0"
                        ::class="{ 'is-active': isAccordionActive }"
                        @click="toggleAccordion"
                    >
                        <x-common.icon
                            name="fas fa-industry"
                            class="m-0"
                        />
                        <span class="ml-2"> Production </span>
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
                        @if (isFeatureEnabled('Job Management'))
                            @can('Read Job')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('jobs.index') }}"
                                        label="Jobs"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('jobs.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('jobs.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                        @if (isFeatureEnabled('Job Management'))
                            @can('Plan Job')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('job-planners.create') }}"
                                        label="Planner"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('job-planners.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('job-planners.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                        @if (isFeatureEnabled('Bill Of Material Management'))
                            @can('Read BOM')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('bill-of-materials.index') }}"
                                        label="Bill Of Materials"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('bill-of-materials.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('bill-of-materials.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                    </ul>
                </li>
            </ul>
        @endcanany
    @endif

    @if (isFeatureEnabled(
            'Employee Management',
            'Department Management',
            'Employee Transfer',
            'Attendance Management',
            'Warning Management',
            'Advancement Management',
            'Leave Management',
            'Expense Claim',
            'Announcement Management',
            'Compensation Management',
            'Compensation Adjustment',
            'Payroll Management'))
        @canany(['Read Employee', 'Read Department', 'Read Employee Transfer', 'Read Attendance', 'Read Warning', 'Read Advancement', 'Read Leave', 'Read Expense Claim', 'Read Announcement', 'Read Compensation', 'Read Compensation Adjustment', 'Read Payroll'])
            <ul
                x-data="sideMenuAccordion"
                class="menu-list mb-2"
            >
                <li>
                    <x-common.button
                        tag="button"
                        mode="button"
                        class="is-fullwidth is-justify-content-left is-borderless text-green is-size-6-5 ml-0"
                        ::class="{ 'is-active': isAccordionActive }"
                        @click="toggleAccordion"
                    >
                        <x-common.icon
                            name="fas fa-users"
                            class="m-0"
                        />
                        <span class="ml-2"> Human Resource </span>
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
                        @if (isFeatureEnabled('Employee Management'))
                            @can('Read Employee')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('employees.index') }}"
                                        label="Employees"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('employees.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('employees.*') ? 'activateAccordion' : '' }}"
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
                                        label="Departments"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('departments.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('departments.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif

                        @if (isFeatureEnabled('Employee Transfer'))
                            @can('Read Employee Transfer')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('employee-transfers.index') }}"
                                        label="Transfers"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('employee-transfers.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('employee-transfers.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif

                        @if (isFeatureEnabled('Attendance Management'))
                            @can('Read Attendance')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('attendances.index') }}"
                                        label="Attendances"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('attendances.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('attendances.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif

                        @if (isFeatureEnabled('Warning Management'))
                            @can('Read Warning')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('warnings.index') }}"
                                        label="Warnings"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('warnings.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('warnings.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif

                        @if (isFeatureEnabled('Advancement Management'))
                            @can('Read Advancement')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('advancements.index') }}"
                                        label="Advancements"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('advancements.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('advancements.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif

                        @if (isFeatureEnabled('Leave Management'))
                            @can('Read Leave')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('leave-categories.index') }}"
                                        label="Leave Categories"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('leave-categories.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('leave-categories.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('leaves.index') }}"
                                        label="Leaves"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('leaves.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('leaves.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif

                        @if (isFeatureEnabled('Expense Claim'))
                            @can('Read Expense Claim')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('expense-claims.index') }}"
                                        label="Expense Claims"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('expense-claims.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('expense-claims.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif

                        @if (isFeatureEnabled('Announcement Management'))
                            @can('Read Announcement')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('announcements.index') }}"
                                        label="Announcements"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('announcements.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('announcements.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif

                        @if (isFeatureEnabled('Compensation Management'))
                            @can('Read Compensation')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('compensations.index') }}"
                                        label="Compensation"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('compensations.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('compensations.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif

                        @if (isFeatureEnabled('Compensation Adjustment'))
                            @can('Read Compensation Adjustment')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('compensation-adjustments.index') }}"
                                        label="Adjustments"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('compensation-adjustments.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('compensation-adjustments.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif

                        @if (isFeatureEnabled('Payroll Management'))
                            @can('Read Payroll')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('payrolls.index') }}"
                                        label="Payrolls"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('payrolls.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('payrolls.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                    </ul>
                </li>
            </ul>
        @endcanany
    @endif

    @if (isFeatureEnabled('Merchandise Inventory', 'Batch Management', 'Warehouse Management', 'Grn Management', 'Transfer Management', 'Damage Management', 'Inventory Adjustment', 'Siv Management'))
        @canany(['Read Available Inventory', 'Read Warehouse', 'Read GRN', 'Read Transfer', 'Read Damage', 'Read Adjustment', 'Read SIV', 'Read Merchandise Batch'])
            <ul
                x-data="sideMenuAccordion"
                class="menu-list mb-2"
            >
                <li>
                    <x-common.button
                        tag="button"
                        mode="button"
                        class="is-fullwidth is-justify-content-left is-borderless text-green is-size-6-5 ml-0"
                        ::class="{ 'is-active': isAccordionActive }"
                        @click="toggleAccordion"
                    >
                        <x-common.icon
                            name="fas fa-warehouse"
                            class="m-0"
                        />
                        <span class="ml-2"> Warehouse & Inventory </span>
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
                        @if (isFeatureEnabled('Merchandise Inventory'))
                            @can('Read Available Inventory')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('merchandises.index', 'available') }}"
                                        label="Inventory Level"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('merchandises.*') ? 'text-green has-text-weight-bold' : '' }} "
                                        x-init="{{ request()->routeIs('merchandises.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                        @if (isFeatureEnabled('Batch Management'))
                            @can('Read Merchandise Batch')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('merchandise-batches.index') }}"
                                        label="Batches"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('merchandise-batches.*') ? 'text-green has-text-weight-bold' : '' }} "
                                        x-init="{{ request()->routeIs('merchandise-batches.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                        @if (isFeatureEnabled('Warehouse Management'))
                            @can('Read Warehouse')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('warehouses.index') }}"
                                        label="Warehouses"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('warehouses.*') ? 'text-green has-text-weight-bold' : '' }} "
                                        x-init="{{ request()->routeIs('warehouses.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                        @if (isFeatureEnabled('Grn Management'))
                            @can('Read GRN')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('grns.index') }}"
                                        label="Goods Received Notes"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('grns.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('grns.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                        @if (isFeatureEnabled('Transfer Management'))
                            @can('Read Transfer')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('transfers.index') }}"
                                        label="Transfers"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('transfers.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('transfers.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                        @if (isFeatureEnabled('Damage Management'))
                            @can('Read Damage')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('damages.index') }}"
                                        label="Damages"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('damages.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('damages.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                        @if (isFeatureEnabled('Inventory Adjustment'))
                            @can('Read Adjustment')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('adjustments.index') }}"
                                        label="Adjustments"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('adjustments.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('adjustments.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                        @if (isFeatureEnabled('Siv Management'))
                            @can('Read SIV')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('sivs.index') }}"
                                        label="Store Issue Vouchers"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('sivs.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('sivs.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                        @foreach (pads('Warehouse & Inventory') as $pad)
                            @canpad('Read', $pad)
                            <li>
                                <x-common.button
                                    tag="a"
                                    href="{{ route('pads.transactions.index', $pad->id) }}"
                                    label="{{ $pad->name }}"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ (request()->routeIs('transactions.*') && request()->route('transaction')->pad_id == $pad->id) || (request()->routeIs('pads.transactions.*') && request()->route('pad')->id == $pad->id) ? 'text-green has-text-weight-bold' : '' }}"
                                    x-init="{{ (request()->routeIs('transactions.*') && request()->route('transaction')->pad_id == $pad->id) || (request()->routeIs('pads.transactions.*') && request()->route('pad')->id == $pad->id) ? 'activateAccordion' : '' }}"
                                />
                            </li>
                            @endcanpad
                        @endforeach
                    </ul>
                </li>
            </ul>
        @endcanany
    @endif

    @if (isFeatureEnabled('Sale Management', 'Gdn Management', 'Proforma Invoice', 'Reservation Management', 'Return Management', 'Customer Management', 'Contact Management', 'Exchange Management'))
        @canany(['Read Sale', 'Read GDN', 'Read Proforma Invoice', 'Read Reservation', 'Read Return', 'Read Customer', 'Read Contact', 'Read Exchange'])
            <ul
                x-data="sideMenuAccordion"
                class="menu-list mb-2"
            >
                <li>
                    <x-common.button
                        tag="button"
                        mode="button"
                        class="is-fullwidth is-justify-content-left is-borderless text-green is-size-6-5 ml-0"
                        ::class="{ 'is-active': isAccordionActive }"
                        @click="toggleAccordion"
                    >
                        <x-common.icon
                            name="fas fa-tags"
                            class="m-0"
                        />
                        <span class="ml-2"> Sales & Customers </span>
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
                        @if (isFeatureEnabled('Sale Management'))
                            @can('Read Sale')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('sales.index') }}"
                                        label="Invoices"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('sales.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('sales.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                        @if (isFeatureEnabled('Gdn Management'))
                            @can('Read GDN')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('gdns.index') }}"
                                        label="Delivery Orders"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('gdns.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('gdns.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                        @if (isFeatureEnabled('Proforma Invoice'))
                            @can('Read Proforma Invoice')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('proforma-invoices.index') }}"
                                        label="Proforma Invoices"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('proforma-invoices.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('proforma-invoices.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                        @if (isFeatureEnabled('Reservation Management'))
                            @can('Read Reservation')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('reservations.index') }}"
                                        label="Reservations"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('reservations.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('reservations.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                        @if (isFeatureEnabled('Return Management'))
                            @can('Read Return')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('returns.index') }}"
                                        label="Returns"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('returns.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('returns.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                        @if (isFeatureEnabled('Customer Management'))
                            @can('Read Customer')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('customers.index') }}"
                                        label="Customers"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('customers.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('customers.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                        @if (isFeatureEnabled('Contact Management'))
                            @can('Read Contact')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('contacts.index') }}"
                                        label="Contacts"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('contacts.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('contacts.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif

                        @if (isFeatureEnabled('Exchange Management'))
                            @can('Read Exchange')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('exchanges.index') }}"
                                        label="Exchanges"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('exchanges.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('exchanges.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif

                        @foreach (pads('Sales & Customers') as $pad)
                            @canpad('Read', $pad)
                            <li>
                                <x-common.button
                                    tag="a"
                                    href="{{ route('pads.transactions.index', $pad->id) }}"
                                    label="{{ $pad->name }}"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ (request()->routeIs('transactions.*') && request()->route('transaction')->pad_id == $pad->id) || (request()->routeIs('pads.transactions.*') && request()->route('pad')->id == $pad->id) ? 'text-green has-text-weight-bold' : '' }}"
                                    x-init="{{ (request()->routeIs('transactions.*') && request()->route('transaction')->pad_id == $pad->id) || (request()->routeIs('pads.transactions.*') && request()->route('pad')->id == $pad->id) ? 'activateAccordion' : '' }}"
                                />
                            </li>
                            @endcanpad
                        @endforeach
                    </ul>
                </li>
            </ul>
        @endcanany
    @endif

    @if (isFeatureEnabled('Tender Management'))
        @can('Read Tender')
            <ul
                x-data="sideMenuAccordion"
                class="menu-list mb-2"
            >
                <li>
                    <x-common.button
                        tag="button"
                        mode="button"
                        class="is-fullwidth is-justify-content-left is-borderless text-green is-size-6-5 ml-0"
                        ::class="{ 'is-active': isAccordionActive }"
                        @click="toggleAccordion"
                    >
                        <x-common.icon
                            name="fas fa-project-diagram"
                            class="m-0"
                        />
                        <span class="ml-2"> Tenders </span>
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
                        <li>
                            <x-common.button
                                tag="a"
                                href="{{ route('tenders.index') }}"
                                label="Tenders"
                                class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('tenders.*') ? 'text-green has-text-weight-bold' : '' }}"
                                x-init="{{ request()->routeIs('tenders.*') ? 'activateAccordion' : '' }}"
                            />
                        </li>
                        <li>
                            <x-common.button
                                tag="a"
                                href="{{ route('tender-opportunities.index') }}"
                                label="Tender Opportunities"
                                class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('tender-opportunities.*') ? 'text-green has-text-weight-bold' : '' }}"
                                x-init="{{ request()->routeIs('tender-opportunities.*') ? 'activateAccordion' : '' }}"
                            />
                        </li>
                        <li>
                            <x-common.button
                                tag="a"
                                href="{{ route('tender-checklist-types.index') }}"
                                label="Checklist Categories"
                                class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('tender-checklist-types.*') ? 'text-green has-text-weight-bold' : '' }}"
                                x-init="{{ request()->routeIs('tender-checklist-types.*') ? 'activateAccordion' : '' }}"
                            />
                        </li>
                        <li>
                            <x-common.button
                                tag="a"
                                href="{{ route('general-tender-checklists.index') }}"
                                label="Available Checklists"
                                class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('general-tender-checklists.*') ? 'text-green has-text-weight-bold' : '' }}"
                                x-init="{{ request()->routeIs('general-tender-checklists.*') ? 'activateAccordion' : '' }}"
                            />
                        </li>
                        <li>
                            <x-common.button
                                tag="a"
                                href="{{ route('tender-statuses.index') }}"
                                label="Available Statuses"
                                class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('tender-statuses.*') ? 'text-green has-text-weight-bold' : '' }}"
                                x-init="{{ request()->routeIs('tender-statuses.*') ? 'activateAccordion' : '' }}"
                            />
                        </li>
                    </ul>
                </li>
            </ul>
        @endcan
    @endif

    @if (isFeatureEnabled('Product Management', 'Price Management', 'Price Increment', 'Brand Management', 'Cost Update Management'))
        @canany(['Read Product', 'Read Price', 'Read Price Increment', 'Read Brand', 'Read Cost Update'])
            <ul
                x-data="sideMenuAccordion"
                class="menu-list mb-2"
            >
                <li>
                    <x-common.button
                        tag="button"
                        mode="button"
                        class="is-fullwidth is-justify-content-left is-borderless text-green is-size-6-5 ml-0"
                        ::class="{ 'is-active': isAccordionActive }"
                        @click="toggleAccordion"
                    >
                        <x-common.icon
                            name="fas fa-th"
                            class="m-0"
                        />
                        <span class="ml-2"> Products & Categories </span>
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
                        <li>
                            <x-common.button
                                tag="a"
                                href="{{ route('products.index') }}"
                                label="Products"
                                class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('products.*') ? 'text-green has-text-weight-bold' : '' }}"
                                x-init="{{ request()->routeIs('products.*') ? 'activateAccordion' : '' }}"
                            />
                        </li>
                        <li>
                            <x-common.button
                                tag="a"
                                href="{{ route('categories.index') }}"
                                label="Categories"
                                class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('categories.*') ? 'text-green has-text-weight-bold' : '' }}"
                                x-init="{{ request()->routeIs('categories.*') ? 'activateAccordion' : '' }}"
                            />
                        </li>
                        @if (isFeatureEnabled('Price Management'))
                            @can('Read Price')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('prices.index') }}"
                                        label="Prices"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('prices.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('prices.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                        @if (isFeatureEnabled('Price Increment'))
                            @can('Read Price Increment')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('price-increments.index') }}"
                                        label="Price Increments"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('price-increments.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('price-increments.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                        @if (isFeatureEnabled('Brand Management'))
                            @can('Read Brand')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('brands.index') }}"
                                        label="Brands"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('brands.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('brands.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                        @if (isFeatureEnabled('Cost Update Management'))
                            @can('Read Cost Update')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('cost-updates.index') }}"
                                        label="Cost Updates"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('cost-updates.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('cost-updates.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                    </ul>
                </li>
            </ul>
        @endcan
    @endif

    @if (isFeatureEnabled('Purchase Management', 'Supplier Management', 'Debt Management', 'Credit Management', 'Expense Management', 'Customer Deposit Management'))
        @canany(['Read Purchase', 'Read Supplier', 'Read Debt', 'Read Credit', 'Read Expense', 'Read Customer Deposit'])
            <ul
                x-data="sideMenuAccordion"
                class="menu-list mb-2"
            >
                <li>
                    <x-common.button
                        tag="button"
                        mode="button"
                        class="is-fullwidth is-justify-content-left is-borderless text-green is-size-6-5 ml-0"
                        ::class="{ 'is-active': isAccordionActive }"
                        @click="toggleAccordion"
                    >
                        <x-common.icon
                            name="fas fa-chart-line"
                            class="m-0"
                        />
                        <span class="ml-2"> Finance </span>
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
                        @if (isFeatureEnabled('Purchase Management'))
                            @can('Read Purchase')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('purchases.index') }}"
                                        label="Purchases"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('purchases.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('purchases.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                        @if (isFeatureEnabled('Supplier Management'))
                            @can('Read Supplier')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('suppliers.index') }}"
                                        label="Suppliers"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('suppliers.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('suppliers.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                        @if (isFeatureEnabled('Debt Management'))
                            @can('Read Debt')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('debts.index') }}"
                                        label="Debts"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('debts.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('debts.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                        @if (isFeatureEnabled('Credit Management'))
                            @can('Read Credit')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('credits.index') }}"
                                        label="Credits"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('credits.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('credits.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                        @if (isFeatureEnabled('Customer Deposit Management'))
                            @can('Read Customer Deposit')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('customer-deposits.index') }}"
                                        label="Deposits"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('customer-deposits.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('customer-deposits.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                        @if (isFeatureEnabled('Expense Management'))
                            @can('Read Expense')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('expense-categories.index') }}"
                                        label="Expense Categories"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('expense-categories.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('expense-categories.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('expenses.index') }}"
                                        label="Expenses"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('expenses.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('expenses.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                        @foreach (pads('Purchase & Suppliers') as $pad)
                            @canpad('Read', $pad)
                            <li>
                                <x-common.button
                                    tag="a"
                                    href="{{ route('pads.transactions.index', $pad->id) }}"
                                    label="{{ $pad->name }}"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ (request()->routeIs('transactions.*') && request()->route('transaction')->pad_id == $pad->id) || (request()->routeIs('pads.transactions.*') && request()->route('pad')->id == $pad->id) ? 'text-green has-text-weight-bold' : '' }}"
                                    x-init="{{ (request()->routeIs('transactions.*') && request()->route('transaction')->pad_id == $pad->id) || (request()->routeIs('pads.transactions.*') && request()->route('pad')->id == $pad->id) ? 'activateAccordion' : '' }}"
                                />
                            </li>
                            @endcanpad
                        @endforeach
                    </ul>
                </li>
            </ul>
        @endcanany
    @endif

    @if (isFeatureEnabled(
            'Sales Report',
            'Return Report',
            'Expense Report',
            'Customer Report',
            'Daily Inventory Level Report',
            'Credit Management',
            'Debt Management',
            'Inventory Transfer Report',
            'Credit Report',
            'Inventory Summary Report',
            'Inventory Valuation Report',
            'Profit Report',
            'Inventory Batch Report',
            'Sale By Payment Report',
            'Inventory In Transit Report'))
        @canany([
            'Read Sale Report',
            'Read Return Report',
            'Read Expense Report',
            'Read Customer Report',
            'Read Daily Inventory Report',
            'Read Credit',
            'Read Debt',
            'Read Inventory Transfer Report',
            'Read Credit Report',
            'Read Inventory Summary Report',
            'Read Inventory Valuation Report',
            'Read
            Profit Report',
            'Read Inventory Batch Report',
            'Read Inventory In Transit Report',
            ])
            <ul
                x-data="sideMenuAccordion"
                class="menu-list mb-2"
            >
                <li>
                    <x-common.button
                        tag="button"
                        mode="button"
                        class="is-fullwidth is-justify-content-left is-borderless text-green is-size-6-5 ml-0"
                        ::class="{ 'is-active': isAccordionActive }"
                        @click="toggleAccordion"
                    >
                        <x-common.icon
                            name="fas fa-chart-pie"
                            class="m-0"
                        />
                        <span class="ml-2"> Report & Analytics </span>
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
                        @if (isFeatureEnabled('Sales Report'))
                            @can('Read Sale Report')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('reports.sale') }}"
                                        label="Sales"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('reports.sale') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('reports.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif

                        @if (isFeatureEnabled('Return Report'))
                            @can('Read Return Report')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('reports.return') }}"
                                        label="Sales Return"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('reports.return') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('reports.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif

                        @if (isFeatureEnabled('Expense Report'))
                            @can('Read Expense Report')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('reports.expense') }}"
                                        label="Expense"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('reports.expense') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('reports.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif

                        @if (isFeatureEnabled('Customer Report'))
                            @can('Read Customer Report')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('reports.customer') }}"
                                        label="Customers"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('reports.customer') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('reports.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif

                        @if (isFeatureEnabled('Daily Inventory Level Report'))
                            @can('Read Daily Inventory Report')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('reports.inventory_level') }}"
                                        label="Daily Inventory Level"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('reports.inventory_level') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('reports.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif

                        @if (isFeatureEnabled('Inventory Transfer Report'))
                            @can('Read Inventory Transfer Report')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('reports.inventory_transfer') }}"
                                        label="Inventory Transfer"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('reports.inventory_transfer') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('reports.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif

                        @if (isFeatureEnabled('Inventory Valuation Report'))
                            @can('Read Inventory Valuation Report')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('reports.inventory_valuation') }}"
                                        label="Inventory Valuation"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('reports.inventory_valuation') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('reports.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif

                        @if (isFeatureEnabled('Credit Report'))
                            @can('Read Credit Report')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('reports.credit') }}"
                                        label="Credit"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('reports.credit') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('reports.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif

                        @if (isFeatureEnabled('Credit Management'))
                            @can('Read Credit')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('receivables.index') }}"
                                        label="Receivables & Aging"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('receivables.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('receivables.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif

                        @if (isFeatureEnabled('Debt Management'))
                            @can('Read Debt')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('payables.index') }}"
                                        label="Payables & Aging"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('payables.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('payables.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif

                        @if (isFeatureEnabled('Inventory Summary Report'))
                            @can('Read Inventory Summary Report')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('reports.inventory_summary') }}"
                                        label="Inventory Summary"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('reports.inventory_summary') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('reports.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif

                        @if (isFeatureEnabled('Profit Report'))
                            @can('Read Profit Report')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('reports.profit') }}"
                                        label="Profit"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('reports.profit') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('reports.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif

                        @if (isFeatureEnabled('Sale By Payment Report'))
                            @can('Read Sale Report')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('reports.sale_by_payment') }}"
                                        label="Sales By Payment"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('reports.sale_by_payment') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('reports.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif

                        @if (isFeatureEnabled('Inventory Batch Report'))
                            @can('Read Inventory Batch Report')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('reports.inventory_batch') }}"
                                        label="Batches"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('reports.inventory_batch') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('reports.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif

                        @if (isFeatureEnabled('Inventory In Transit Report'))
                            @can('Read Inventory In Transit Report')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('reports.inventory_in_transit') }}"
                                        label="Inventory In Transit"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('reports.inventory_in_transit') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('reports.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                    </ul>
                </li>
            </ul>
        @endcanany
    @endif

    @if (isFeatureEnabled('User Management', 'General Settings', 'Custom Field Management'))
        @canany(['Update Company', 'Read Custom Field'])
            <ul
                x-data="sideMenuAccordion"
                class="menu-list mb-2"
            >
                <li>
                    <x-common.button
                        tag="button"
                        mode="button"
                        class="is-fullwidth is-justify-content-left is-borderless text-green is-size-6-5 ml-0"
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
                        @if (isFeatureEnabled('Custom Field Management'))
                            @can('Read Custom Field')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('custom-fields.index') }}"
                                        label="Custom Fields"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('custom-fields.*') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('custom-fields.*') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                        @if (isFeatureEnabled('User Management') && !isFeatureEnabled('Employee Management'))
                            @can('Read Employee')
                                <li>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('employees.index') }}"
                                        label="Employees"
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('employees.*') ? 'text-green has-text-weight-bold' : '' }}"
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
                                        class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('companies.edit') ? 'text-green has-text-weight-bold' : '' }}"
                                        x-init="{{ request()->routeIs('companies.edit') ? 'activateAccordion' : '' }}"
                                    />
                                </li>
                            @endcan
                        @endif
                        @foreach (pads('General Settings') as $pad)
                            @canpad('Read', $pad)
                            <li>
                                <x-common.button
                                    tag="a"
                                    href="{{ route('pads.transactions.index', $pad->id) }}"
                                    label="{{ $pad->name }}"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ (request()->routeIs('transactions.*') && request()->route('transaction')->pad_id == $pad->id) || (request()->routeIs('pads.transactions.*') && request()->route('pad')->id == $pad->id) ? 'text-green has-text-weight-bold' : '' }}"
                                    x-init="{{ (request()->routeIs('transactions.*') && request()->route('transaction')->pad_id == $pad->id) || (request()->routeIs('pads.transactions.*') && request()->route('pad')->id == $pad->id) ? 'activateAccordion' : '' }}"
                                />
                            </li>
                            @endcanpad
                        @endforeach
                    </ul>
                </li>
            </ul>
        @endcanany
    @endif
</aside>
