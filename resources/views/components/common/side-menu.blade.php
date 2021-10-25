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
                        {{ auth()->user()->name }}
                    </div>
                    <div class="has-text-grey is-size-7 is-capitalized">
                        {{ auth()->user()->employee->position ?? 'Job: Not Assigned' }}
                    </div>
                </div>
            </div>
            <div class="buttons is-hidden-tablet ml-3 mt-5">
                <x-common.button
                    tag="a"
                    mode="button"
                    href="{{ route('employees.show', auth()->user()->employee->id) }}"
                    icon="fas fa-address-card"
                    label="My Profile"
                    class="bg-green has-text-white is-small"
                />
                <x-common.button
                    tag="a"
                    mode="button"
                    href="{{ route('logout') }}"
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                    icon="fas fa-power-off"
                    label="Logout"
                    class="btn-purple is-outlined is-small"
                />
                <form
                    action="{{ route('logout') }}"
                    method="POST"
                    style="display: none;"
                >
                    @csrf
                </form>
            </div>
        </li>
    </ul>

    <hr>

    <ul class="menu-list mb-2">
        <li>
            <x-common.button
                tag="a"
                href="/"
                name="menuTitles"
                class="text-green is-size-6-5 has-text-left {{ request()->is('/') ? 'is-active' : '' }}"
            >
                <x-common.icon
                    name="fas fa-bars"
                    class="pl-1"
                />
                <span class="ml-1"> Main Menu </span>
            </x-common.button>
        </li>
    </ul>

    @canany(['Read Merchandise', 'Read Warehouse', 'Read GRN', 'Read Transfer', 'Read Damage', 'Read Adjustment', 'Read SIV'])
        <ul class="menu-list mb-2">
            <li>
                <x-common.button
                    tag="button"
                    mode="button"
                    name="menu-accordion"
                    class="is-fullwidth is-justify-content-left is-borderless text-green is-size-6-5 ml-0"
                >
                    <x-common.icon
                        name="fas fa-warehouse"
                        class="m-0"
                    />
                    <span class="ml-2"> Warehouse & Inventory </span>
                    <x-common.icon
                        name="fas fa-caret-down"
                        class="ml-auto"
                    />
                </x-common.button>
            </li>
            <li>
                <ul class="mt-0 ml-5 is-hidden">
                    @can('Read Merchandise')
                        @if (isFeatureEnabled('Merchandise Inventory'))
                            <li>
                                <x-common.button
                                    tag="a"
                                    href="{{ route('merchandises.index', 'on-hand') }}"
                                    name="menuTitles"
                                    label="Inventory Level"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('merchandises') ? 'is-active' : '' }}"
                                />
                            </li>
                        @endif
                    @endcan
                    @can('Read Warehouse')
                        @if (isFeatureEnabled('Warehouse Management'))
                            <li>
                                <x-common.button
                                    tag="a"
                                    href="{{ route('warehouses.index') }}"
                                    name="menuTitles"
                                    label="Warehouses"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('warehouses') ? 'is-active' : '' }}"
                                />
                            </li>
                        @endif
                    @endcan
                    @if (isFeatureEnabled('Grn Management'))
                        @can('Read GRN')
                            <li>
                                <x-common.button
                                    tag="a"
                                    href="{{ route('grns.index') }}"
                                    name="menuTitles"
                                    label="Goods Received Notes"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('grns') ? 'is-active' : '' }}"
                                />
                            </li>
                        @endif
                    @endcan
                    @if (isFeatureEnabled('Transfer Management'))
                        @can('Read Transfer')
                            <li>
                                <x-common.button
                                    tag="a"
                                    href="{{ route('transfers.index') }}"
                                    name="menuTitles"
                                    label="Transfers"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('transfers') ? 'is-active' : '' }}"
                                />
                            </li>
                        @endif
                    @endcan
                    @if (isFeatureEnabled('Damage Management'))
                        @can('Read Damage')
                            <li>
                                <x-common.button
                                    tag="a"
                                    href="{{ route('damages.index') }}"
                                    name="menuTitles"
                                    label="Damages"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('damages') ? 'is-active' : '' }}"
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
                                    name="menuTitles"
                                    label="Adjustments"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('adjustments') ? 'is-active' : '' }}"
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
                                    name="menuTitles"
                                    label="Store Issue Vouchers"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('sivs') ? 'is-active' : '' }}"
                                />
                            </li>
                        @endcan
                    @endif
                </ul>
            </li>
        </ul>
    @endcanany

    @canany(['Read Sale', 'Read GDN', 'Read Proforma Invoice', 'Read Reservation', 'Read Return', 'Read PO', 'Read Customer'])
        <ul class="menu-list mb-2">
            <li>
                <x-common.button
                    tag="button"
                    mode="button"
                    name="menu-accordion"
                    class="is-fullwidth is-justify-content-left is-borderless text-green is-size-6-5 ml-0"
                >
                    <x-common.icon
                        name="fas fa-tags"
                        class="m-0"
                    />
                    <span class="ml-2"> Sales & Customers </span>
                    <x-common.icon
                        name="fas fa-caret-down"
                        class="ml-auto"
                    />
                </x-common.button>
            </li>
            <li>
                <ul class="mt-0 ml-5 is-hidden">
                    @if (isFeatureEnabled('Sale Management'))
                        @can('Read Sale')
                            <li>
                                <x-common.button
                                    tag="a"
                                    href="{{ route('sales.index') }}"
                                    name="menuTitles"
                                    label="Invoices"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('sales') ? 'is-active' : '' }}"
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
                                    name="menuTitles"
                                    label="Delivery Orders"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('gdns') ? 'is-active' : '' }}"
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
                                    name="menuTitles"
                                    label="Proforma Invoices"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('proforma-invoices') ? 'is-active' : '' }}"
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
                                    name="menuTitles"
                                    label="Reservations"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('reservations') ? 'is-active' : '' }}"
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
                                    name="menuTitles"
                                    label="Returns"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('returns') ? 'is-active' : '' }}"
                                />
                            </li>
                        @endcan
                    @endif
                    @can('Read PO')
                        @if (isFeatureEnabled('Purchase Order'))
                            <li>
                                <x-common.button
                                    tag="a"
                                    href="{{ route('purchase-orders.index') }}"
                                    name="menuTitles"
                                    label="Purchase Orders"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('purchase-orders') ? 'is-active' : '' }}"
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
                                    name="menuTitles"
                                    label="Customers"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('customers') ? 'is-active' : '' }}"
                                />
                            </li>
                        @endcan
                    @endif
                </ul>
            </li>
        </ul>
    @endcanany

    @can('Read Tender')
        @if (isFeatureEnabled('Tender Management'))
            <ul class="menu-list mb-2">
                <li>
                    <x-common.button
                        tag="button"
                        mode="button"
                        name="menu-accordion"
                        class="is-fullwidth is-justify-content-left is-borderless text-green is-size-6-5 ml-0"
                    >
                        <x-common.icon
                            name="fas fa-project-diagram"
                            class="m-0"
                        />
                        <span class="ml-2"> Tenders </span>
                        <x-common.icon
                            name="fas fa-caret-down"
                            class="ml-auto"
                        />
                    </x-common.button>
                </li>
                <li>
                    <ul class="mt-0 ml-5 is-hidden">
                        <li>
                            <x-common.button
                                tag="a"
                                href="{{ route('tenders.index') }}"
                                name="menuTitles"
                                label="Tenders"
                                class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('tenders') ? 'is-active' : '' }}"
                            />
                        </li>
                        <li>
                            <x-common.button
                                tag="a"
                                href="{{ route('tender-checklist-types.index') }}"
                                name="menuTitles"
                                label="Checklist Categories"
                                class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('tender-checklist-types') ? 'is-active' : '' }}"
                            />
                        </li>
                        <li>
                            <x-common.button
                                tag="a"
                                href="{{ route('general-tender-checklists.index') }}"
                                name="menuTitles"
                                label="Available Checklists"
                                class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('general-tender-checklists') ? 'is-active' : '' }}"
                            />
                        </li>
                        <li>
                            <x-common.button
                                tag="a"
                                href="{{ route('tender-statuses.index') }}"
                                name="menuTitles"
                                label="Available Statuses"
                                class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('tender-statuses') ? 'is-active' : '' }}"
                            />
                        </li>
                    </ul>
                </li>
            </ul>
        @endif
    @endcan

    @canany(['Read Purchase', 'Read Supplier'])
        <ul class="menu-list mb-2">
            <li>
                <x-common.button
                    tag="button"
                    mode="button"
                    name="menu-accordion"
                    class="is-fullwidth is-justify-content-left is-borderless text-green is-size-6-5 ml-0"
                >
                    <x-common.icon
                        name="fas fa-shopping-bag"
                        class="m-0"
                    />
                    <span class="ml-2"> Purchases & Suppliers </span>
                    <x-common.icon
                        name="fas fa-caret-down"
                        class="ml-auto"
                    />
                </x-common.button>
            </li>
            <li>
                <ul class="mt-0 ml-5 is-hidden">
                    @if (isFeatureEnabled('Purchase Management'))
                        @can('Read Purchase')
                            <li>
                                <x-common.button
                                    tag="a"
                                    href="{{ route('purchases.index') }}"
                                    name="menuTitles"
                                    label="Purchases"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('purchases') ? 'is-active' : '' }}"
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
                                    name="menuTitles"
                                    label="Suppliers"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('suppliers') ? 'is-active' : '' }}"
                                />
                            </li>
                        @endcan
                    @endif
                </ul>
            </li>
        </ul>
    @endcanany

    @can('Read Product')
        @if (isFeatureEnabled('Product Management'))
            <ul class="menu-list mb-2">
                <li>
                    <x-common.button
                        tag="button"
                        mode="button"
                        name="menu-accordion"
                        class="is-fullwidth is-justify-content-left is-borderless text-green is-size-6-5 ml-0"
                    >
                        <x-common.icon
                            name="fas fa-th"
                            class="m-0"
                        />
                        <span class="ml-2"> Products & Categories </span>
                        <x-common.icon
                            name="fas fa-caret-down"
                            class="ml-auto"
                        />
                    </x-common.button>
                </li>
                <li>
                    <ul class="mt-0 ml-5 is-hidden">
                        <li>
                            <x-common.button
                                tag="a"
                                href="{{ route('products.index') }}"
                                name="menuTitles"
                                label="Products"
                                class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('products') ? 'is-active' : '' }}"
                            />
                        </li>
                        <li>
                            <x-common.button
                                tag="a"
                                href="{{ route('categories.index') }}"
                                name="menuTitles"
                                label="Categories"
                                class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('categories') ? 'is-active' : '' }}"
                            />
                        </li>
                    </ul>
                </li>
            </ul>
        @endif
    @endcan

    @canany(['Read Employee', 'Update Company'])
        <ul class="menu-list mb-2">
            <li>
                <x-common.button
                    tag="button"
                    mode="button"
                    name="menu-accordion"
                    class="is-fullwidth is-justify-content-left is-borderless text-green is-size-6-5 ml-0"
                >
                    <x-common.icon
                        name="fas fa-cog"
                        class="m-0"
                    />
                    <span class="ml-2"> Settings </span>
                    <x-common.icon
                        name="fas fa-caret-down"
                        class="ml-auto"
                    />
                </x-common.button>
            </li>
            <li>
                <ul class="mt-0 ml-5 is-hidden">
                    @if (isFeatureEnabled('User Management'))
                        @can('Read Employee')
                            <li>
                                <x-common.button
                                    tag="a"
                                    href="{{ route('employees.index') }}"
                                    name="menuTitles"
                                    label="Users"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('employees') ? 'is-active' : '' }}"
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
                                    name="menuTitles"
                                    label="Company Profile"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->is('companies') ? 'is-active' : '' }}"
                                />
                            </li>
                        @endcan
                    @endif
                </ul>
            </li>
        </ul>
    @endcanany
</aside>
