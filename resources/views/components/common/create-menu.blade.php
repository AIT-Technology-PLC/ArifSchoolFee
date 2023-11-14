<div
    x-data="toggler"
    @open-create-modal.window="toggle"
    class="modal is-active"
    x-cloak
    x-show="!isHidden"
    x-transition
>
    <div
        class="modal-background"
        @click="toggle"
    ></div>
    <div class="modal-content p-lr-20">
        <div class="box is-radiusless bg-lightgreen">
            <h1 class="has-text-centered mb-3 is-uppercase text-green has-text-weight-bold">
                <x-common.icon name="fas fa-plus" />
                <span> Create New </span>
            </h1>

            @if (isFeatureEnabled('Bill Of Material Management', 'Job Management'))
                @canany(['Create BOM', 'Create Job'])
                    <x-content.header>
                        <x-slot name="header">
                            <x-common.icon
                                name="fas fa-industry"
                                class="is-size-6 text-green"
                            />
                            <span class="ml-2 is-size-6 text-green"> Production </span>
                        </x-slot>
                    </x-content.header>
                    <x-content.footer>
                        <div class="columns is-marginless is-multiline is-mobile">
                            @if (isFeatureEnabled('Job Management'))
                                @can('Create Job')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('jobs.create') }}"
                                            icon="fas fa-cogs"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Job </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Job Management'))
                                @can('Plan Job')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('job-planners.create') }}"
                                            icon="fas fa-chart-bar"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Planner </span>
                                        <br>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Bill Of Material Management'))
                                @can('Create BOM')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('bill-of-materials.create') }}"
                                            icon="fas fa-clipboard-list"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Bill Of Material </span>
                                    </div>
                                @endcan
                            @endif
                        </div>
                    </x-content.footer>
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
                @canany(['Create Employee', 'Create Department', 'Create Employee Transfer', 'Create Attendance', 'Create Warning', 'Create Advancement', 'Create Leave', 'Create Expense Claim', 'Create Announcement', 'Create Compensation', 'Create Compensation Adjustment', 'Create Payroll'])
                    <x-content.header>
                        <x-slot name="header">
                            <x-common.icon
                                name="fas fa-users"
                                class="is-size-6 text-green"
                            />
                            <span class="ml-2 is-size-6 text-green"> Human Resource </span>
                        </x-slot>
                    </x-content.header>
                    <x-content.footer>
                        <div class="columns is-marginless is-multiline is-mobile">
                            @if (isFeatureEnabled('Employee Management'))
                                @can('Create Employee')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('employees.create') }}"
                                            icon="fas fa-user"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Employee </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Department Management'))
                                @can('Create Department')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('departments.create') }}"
                                            icon="fas fa-users-rectangle"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Department </span>
                                        <br>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Employee Transfer'))
                                @can('Create Employee Transfer')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('employee-transfers.create') }}"
                                            icon="fas fa-people-arrows-left-right"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Transfer </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Attendance Management'))
                                @can('Create Attendance')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('attendances.create') }}"
                                            icon="fa-solid fa-user-clock"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Attendance </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Warning Management'))
                                @can('Create Warning')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('warnings.create') }}"
                                            icon="fa-solid fa-exclamation-circle"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Warning </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Advancement Management'))
                                @can('Create Advancement')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('advancements.create') }}"
                                            icon="fas fa-arrows-up-down"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Advancement </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Leave Management'))
                                @can('Create Leave')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('leave-categories.create') }}"
                                            icon="fa-solid fa-umbrella-beach"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Leave Category </span>
                                    </div>
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('leaves.create') }}"
                                            icon="fa-solid fa-umbrella-beach"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Leave </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Expense Claim'))
                                @can('Create Expense Claim')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('expense-claims.create') }}"
                                            icon="fa-solid fa-file-invoice-dollar"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Expense Claim </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Announcement Management'))
                                @can('Create Announcement')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('announcements.create') }}"
                                            icon="fa-solid fa-rss"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Announcement </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Compensation Management'))
                                @can('Create Compensation')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('compensations.create') }}"
                                            icon="fa-solid fa-circle-dollar-to-slot"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Compensation </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Compensation Adjustment'))
                                @can('Create Compensation Adjustment')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('compensation-adjustments.create') }}"
                                            icon="fa-solid fa-circle-dollar-to-slot"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Adjustment </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Payroll Management'))
                                @can('Create Payroll')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('payrolls.create') }}"
                                            icon="fa-solid fa-coins"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Payroll </span>
                                    </div>
                                @endcan
                            @endif
                        </div>
                    </x-content.footer>
                @endcanany
            @endif

            @if (isFeatureEnabled('Merchandise Inventory', 'Warehouse Management', 'Grn Management', 'Transfer Management', 'Damage Management', 'Inventory Adjustment', 'Siv Management'))
                @canany(['Create Merchandise', 'Create Warehouse', 'Create GRN', 'Create Transfer', 'Create Damage', 'Create Adjustment', 'Create SIV'])
                    <x-content.header>
                        <x-slot name="header">
                            <x-common.icon
                                name="fas fa-warehouse"
                                class="is-size-6 text-green"
                            />
                            <span class="ml-2 is-size-6 text-green"> Warehouse & Inventory </span>
                        </x-slot>
                    </x-content.header>
                    <x-content.footer>
                        <div class="columns is-marginless is-multiline is-mobile">
                            @if (isFeatureEnabled('Warehouse Management'))
                                @can('Create Warehouse')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('warehouses.create') }}"
                                            icon="fas fa-warehouse"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Warehouse </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Grn Management'))
                                @can('Create GRN')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('grns.create') }}"
                                            icon="fas fa-file-import"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New GRN </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Transfer Management'))
                                @can('Create Transfer')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('transfers.create') }}"
                                            icon="fas fa-exchange-alt"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Transfer </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Damage Management'))
                                @can('Create Damage')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('damages.create') }}"
                                            icon="fas fa-bolt"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Damage </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Inventory Adjustment'))
                                @can('Create Adjustment')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('adjustments.create') }}"
                                            icon="fas fa-eraser"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Adjustment </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Siv Management'))
                                @can('Create SIV')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('sivs.create') }}"
                                            icon="fas fa-file-export"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New SIV </span>
                                    </div>
                                @endcan
                            @endif

                            @foreach (pads('Warehouse & Inventory') as $pad)
                                @canpad('Create', $pad)
                                <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                    <x-common.button
                                        tag="a"
                                        mode="button"
                                        href="{{ route('pads.transactions.create', $pad->id) }}"
                                        icon="{{ $pad->icon }}"
                                        class="text-green bg-lightgreen is-borderless"
                                    />
                                    <br>
                                    <span class="is-size-7"> New {{ $pad->abbreviation }} </span>
                                </div>
                                @endcanpad
                            @endforeach
                        </div>
                    </x-content.footer>
                @endcanany
            @endif

            @if (isFeatureEnabled('Sale Management', 'Gdn Management', 'Proforma Invoice', 'Reservation Management', 'Return Management', 'Customer Management', 'Contact Management', 'Exchange Management'))
                @canany(['Create Sale', 'Create GDN', 'Create Proforma Invoice', 'Create Reservation', 'Create Return', 'Create Customer', 'Create Contact', 'Create Exchange'])
                    <x-content.header>
                        <x-slot name="header">
                            <x-common.icon
                                name="fas fa-tags"
                                class="is-size-6 text-green"
                            />
                            <span class="ml-2 is-size-6 text-green"> Sales & Customers </span>
                        </x-slot>
                    </x-content.header>
                    <x-content.footer>
                        <div class="columns is-marginless is-multiline is-mobile">
                            @if (isFeatureEnabled('Sale Management'))
                                @can('Create Sale')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('sales.create') }}"
                                            icon="fas fa-cash-register"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Invoice </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Gdn Management'))
                                @can('Create GDN')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('gdns.create') }}"
                                            icon="fas fa-file-invoice"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New DO </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Proforma Invoice'))
                                @can('Create Proforma Invoice')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('proforma-invoices.create') }}"
                                            icon="fas fa-receipt"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Proforma Invoices </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Reservation Management'))
                                @can('Create Reservation')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('reservations.create') }}"
                                            icon="fas fa-archive"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Reservation </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Return Management'))
                                @can('Create Return')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('returns.create') }}"
                                            icon="fas fa-arrow-alt-circle-left"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Return </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Customer Management'))
                                @can('Create Customer')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('customers.create') }}"
                                            icon="fas fa-address-card"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Customer </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Contact Management'))
                                @can('Create Contact')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('contacts.create') }}"
                                            icon="fas fa-address-card"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Contact </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Exchange Management'))
                                @can('Create Exchange')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('exchanges.create') }}"
                                            icon="fa-solid fa-arrow-right-arrow-left"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Exchanges </span>
                                    </div>
                                @endcan
                            @endif

                            @foreach (pads('Sales & Customers') as $pad)
                                @canpad('Create', $pad)
                                <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                    <x-common.button
                                        tag="a"
                                        mode="button"
                                        href="{{ route('pads.transactions.create', $pad->id) }}"
                                        icon="{{ $pad->icon }}"
                                        class="text-green bg-lightgreen is-borderless"
                                    />
                                    <br>
                                    <span class="is-size-7"> New {{ $pad->abbreviation }} </span>
                                </div>
                                @endcanpad
                            @endforeach
                        </div>
                    </x-content.footer>
                @endcanany
            @endif

            @if (isFeatureEnabled('Tender Management'))
                @can('Create Tender')
                    <x-content.header>
                        <x-slot name="header">
                            <x-common.icon
                                name="fas fa-project-diagram"
                                class="is-size-6 text-green"
                            />
                            <span class="ml-2 is-size-6 text-green"> Tenders </span>
                        </x-slot>
                    </x-content.header>
                    <x-content.footer>
                        <div class="columns is-marginless is-multiline is-mobile">
                            <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                <x-common.button
                                    tag="a"
                                    mode="button"
                                    href="{{ route('tenders.create') }}"
                                    icon="fas fa-project-diagram"
                                    class="text-green bg-lightgreen is-borderless"
                                />
                                <br>
                                <span class="is-size-7"> New Tender </span>
                            </div>
                            <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                <x-common.button
                                    tag="a"
                                    mode="button"
                                    href="{{ route('tender-opportunities.create') }}"
                                    icon="fas fa-comment-dollar"
                                    class="text-green bg-lightgreen is-borderless"
                                />
                                <br>
                                <span class="is-size-7"> New Tender Opportunity </span>
                            </div>
                            <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                <x-common.button
                                    tag="a"
                                    mode="button"
                                    href="{{ route('tender-checklist-types.create') }}"
                                    icon="fas fa-tasks"
                                    class="text-green bg-lightgreen is-borderless"
                                />
                                <br>
                                <span class="is-size-7"> New Checklist Category </span>
                            </div>
                            <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                <x-common.button
                                    tag="a"
                                    mode="button"
                                    href="{{ route('general-tender-checklists.create') }}"
                                    icon="fas fa-check-double"
                                    class="text-green bg-lightgreen is-borderless"
                                />
                                <br>
                                <span class="is-size-7"> New Checklist </span>
                            </div>
                            <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                <x-common.button
                                    tag="a"
                                    mode="button"
                                    href="{{ route('tender-statuses.create') }}"
                                    icon="fas fa-info"
                                    class="text-green bg-lightgreen is-borderless"
                                />
                                <br>
                                <span class="is-size-7"> New Status </span>
                            </div>
                        </div>
                    </x-content.footer>
                @endcan
            @endif

            @if (isFeatureEnabled('Product Management', 'Price Management', 'Price Increment', 'Brand Management', 'Cost Update Management'))
                @canany(['Create Product', 'Create Price', 'Create Price Increment', 'Create Brand', 'Create Cost Update'])
                    <x-content.header>
                        <x-slot name="header">
                            <x-common.icon
                                name="fas fa-th"
                                class="is-size-6 text-green"
                            />
                            <span class="ml-2 is-size-6 text-green"> Products & Categories </span>
                        </x-slot>
                    </x-content.header>
                    <x-content.footer>
                        <div class="columns is-marginless is-multiline is-mobile">
                            <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                <x-common.button
                                    tag="a"
                                    mode="button"
                                    href="{{ route('categories.create') }}"
                                    icon="fas fa-layer-group"
                                    class="text-green bg-lightgreen is-borderless"
                                />
                                <br>
                                <span class="is-size-7"> New Category </span>
                            </div>
                            <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                <x-common.button
                                    tag="a"
                                    mode="button"
                                    href="{{ route('products.create') }}"
                                    icon="fas fa-th"
                                    class="text-green bg-lightgreen is-borderless"
                                />
                                <br>
                                <span class="is-size-7"> New Product </span>
                            </div>

                            @if (isFeatureEnabled('Price Management'))
                                @can('Create Price')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('prices.create') }}"
                                            icon="fas fa-tags"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Price </span>
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Price Increment'))
                                @can('Create Price Increment')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('price-increments.create') }}"
                                            icon="fas fa-tags"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Price Increment </span>
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Brand Management'))
                                @can('Create Brand')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('brands.create') }}"
                                            icon="fas fa-trademark"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Brand </span>
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Cost Update Management'))
                                @can('Create Cost Update')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('cost-updates.create') }}"
                                            icon="fas fa-tags"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Cost Update </span>
                                        </span>
                                    </div>
                                @endcan
                            @endif
                        </div>
                    </x-content.footer>
                @endcan
            @endif

            @if (isFeatureEnabled('Purchase Management', 'Supplier Management', 'Debt Management', 'Credit Management', 'Expense Management', 'Customer Deposit Management'))
                @canany(['Create Purchase', 'Create Supplier', 'Create Debt', 'Create Credit', 'Create Expense', 'Create Customer Deposit'])
                    <x-content.header>
                        <x-slot name="header">
                            <x-common.icon
                                name="fas fa-chart-line"
                                class="is-size-6 text-green"
                            />
                            <span class="ml-2 is-size-6 text-green"> Finance </span>
                        </x-slot>
                    </x-content.header>
                    <x-content.footer>
                        <div class="columns is-marginless is-multiline is-mobile">
                            @if (isFeatureEnabled('Purchase Management'))
                                @can('Create Purchase')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('purchases.create') }}"
                                            icon="fas fa-shopping-bag"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Purchase </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Supplier Management'))
                                @can('Create Supplier')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('suppliers.create') }}"
                                            icon="fas fa-address-card"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Supplier </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Debt Management'))
                                @can('Create Debt')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('debts.create') }}"
                                            icon="fas fa-money-check-dollar"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Debt </span>
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Credit Management'))
                                @can('Create Credit')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('credits.create') }}"
                                            icon="fas fa-money-check"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Credit </span>
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Customer Deposit Management'))
                                @can('Create Customer Deposit')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('customer-deposits.create') }}"
                                            icon="fa-solid fa-sack-dollar"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Deposit </span>
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Expense Management'))
                                @can('Create Expense')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('expense-categories.create') }}"
                                            icon="fa-solid fa-money-bill-trend-up"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Expense Category </span>
                                    </div>
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('expenses.create') }}"
                                            icon="fa-solid fa-money-bill-trend-up"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Expense </span>
                                    </div>
                                @endcan
                            @endif

                            @foreach (pads('Purchase & Suppliers') as $pad)
                                @canpad('Create', $pad)
                                <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                    <x-common.button
                                        tag="a"
                                        mode="button"
                                        href="{{ route('pads.transactions.create', $pad->id) }}"
                                        icon="{{ $pad->icon }}"
                                        class="text-green bg-lightgreen is-borderless"
                                    />
                                    <br>
                                    <span class="is-size-7"> New {{ $pad->abbreviation }} </span>
                                </div>
                                @endcanpad
                            @endforeach
                        </div>
                    </x-content.footer>
                @endcanany
            @endif

            @if (isFeatureEnabled('Pad Management', 'Custom Field Management') || (isFeatureEnabled('User Management') && !isFeatureEnabled('Employee Management')))
                @canany(['Create Pad', 'Create Employee', 'Create Custom Field'])
                    <x-content.header>
                        <x-slot name="header">
                            <x-common.icon
                                name="fas fa-users"
                                class="is-size-6 text-green"
                            />
                            <span class="ml-2 is-size-6 text-green"> Settings </span>
                        </x-slot>
                    </x-content.header>
                    <x-content.footer>
                        <div class="columns is-marginless is-multiline is-mobile">
                            @if (isFeatureEnabled('Pad Management'))
                                @can('Create Pad')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('pads.create') }}"
                                            icon="fas fa-book"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Pad </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Custom Field Management'))
                                @can('Create Custom Field')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('custom-fields.create') }}"
                                            icon="fas fa-table"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Custom Field </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('User Management') && !isFeatureEnabled('Employee Management'))
                                @can('Create Employee')
                                    <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                        <x-common.button
                                            tag="a"
                                            mode="button"
                                            href="{{ route('employees.create') }}"
                                            icon="fas fa-users"
                                            class="text-green bg-lightgreen is-borderless"
                                        />
                                        <br>
                                        <span class="is-size-7"> New Employee </span>
                                    </div>
                                @endcan
                            @endif

                            @foreach (pads('General Settings') as $pad)
                                @canpad('Create', $pad)
                                <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                    <x-common.button
                                        tag="a"
                                        mode="button"
                                        href="{{ route('pads.transactions.create', $pad->id) }}"
                                        icon="{{ $pad->icon }}"
                                        class="text-green bg-lightgreen is-borderless"
                                    />
                                    <br>
                                    <span class="is-size-7"> New {{ $pad->abbreviation }} </span>
                                </div>
                                @endcanpad
                            @endforeach
                        </div>
                    </x-content.footer>
                @endcan
            @endif
        </div>
    </div>
    <x-common.button
        tag="button"
        class="modal-close is-large"
        @click="toggle"
    />
</div>
