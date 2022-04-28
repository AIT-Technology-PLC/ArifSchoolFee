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

    @canany(['Read Merchandise', 'Read Warehouse', 'Read GRN', 'Read Transfer', 'Read Damage', 'Read Adjustment', 'Read SIV'])
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
                    @can('Read Merchandise')
                        @if (isFeatureEnabled('Merchandise Inventory'))
                            <li>
                                <x-common.button
                                    tag="a"
                                    href="{{ route('merchandises.index', 'available') }}"
                                    label="Inventory Level"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('merchandises.*') ? 'text-green has-text-weight-bold' : '' }} "
                                    x-init="{{ request()->routeIs('merchandises.*') ? 'activateAccordion' : '' }}"
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
                                    label="Warehouses"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('warehouses.*') ? 'text-green has-text-weight-bold' : '' }} "
                                    x-init="{{ request()->routeIs('warehouses.*') ? 'activateAccordion' : '' }}"
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
                                    label="Goods Received Notes"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('grns.*') ? 'text-green has-text-weight-bold' : '' }}"
                                    x-init="{{ request()->routeIs('grns.*') ? 'activateAccordion' : '' }}"
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
                                    label="Transfers"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('transfers.*') ? 'text-green has-text-weight-bold' : '' }}"
                                    x-init="{{ request()->routeIs('transfers.*') ? 'activateAccordion' : '' }}"
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
                        @if ($pad->isEnabled())
                            {{-- @can('') --}}
                            <li>
                                <x-common.button
                                    tag="a"
                                    href="{{ route('pads.transactions.index', $pad->id) }}"
                                    label="{{ $pad->name }}"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('pads.transactions.*') && request()->route('pad')->id == $pad->id? 'text-green has-text-weight-bold': '' }}"
                                    x-init="{{ request()->routeIs('pads.transactions.*') && request()->route('pad')->id == $pad->id? 'activateAccordion': '' }}"
                                />
                            </li>
                            {{-- @endcan --}}
                        @endif
                    @endforeach
                </ul>
            </li>
        </ul>
    @endcanany

    @canany(['Read Sale', 'Read GDN', 'Read Proforma Invoice', 'Read Reservation', 'Read Return', 'Read PO', 'Read Credit', 'Read Price', 'Read Customer'])
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
                    @can('Read Credit')
                        @if (isFeatureEnabled('Credit Management'))
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
                    @can('Read Price')
                        @if (isFeatureEnabled('Price Management'))
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
                    @foreach (pads('Sales & Customers') as $pad)
                        @if ($pad->isEnabled())
                            {{-- @can('') --}}
                            <li>
                                <x-common.button
                                    tag="a"
                                    href="{{ route('pads.transactions.index', $pad->id) }}"
                                    label="{{ $pad->name }}"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('pads.transactions.*') && request()->route('pad')->id == $pad->id? 'text-green has-text-weight-bold': '' }}"
                                    x-init="{{ request()->routeIs('pads.transactions.*') && request()->route('pad')->id == $pad->id? 'activateAccordion': '' }}"
                                />
                            </li>
                            {{-- @endcan --}}
                        @endif
                    @endforeach
                </ul>
            </li>
        </ul>
    @endcanany

    @can('Read Tender')
        @if (isFeatureEnabled('Tender Management'))
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
        @endif
    @endcan

    @canany(['Read Purchase', 'Read Supplier'])
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
                        name="fas fa-shopping-bag"
                        class="m-0"
                    />
                    <span class="ml-2"> Purchases & Suppliers </span>
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
                    @foreach (pads('Purchases & Suppliers') as $pad)
                        @if ($pad->isEnabled())
                            {{-- @can('') --}}
                            <li>
                                <x-common.button
                                    tag="a"
                                    href="{{ route('pads.transactions.index', $pad->id) }}"
                                    label="{{ $pad->name }}"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('pads.transactions.*') && request()->route('pad')->id == $pad->id? 'text-green has-text-weight-bold': '' }}"
                                    x-init="{{ request()->routeIs('pads.transactions.*') && request()->route('pad')->id == $pad->id? 'activateAccordion': '' }}"
                                />
                            </li>
                            {{-- @endcan --}}
                        @endif
                    @endforeach
                </ul>
            </li>
        </ul>
    @endcanany

    @can('Read Product')
        @if (isFeatureEnabled('Product Management'))
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
                    </ul>
                </li>
            </ul>
        @endif
    @endcan

    @canany(['Read Pad', 'Read Employee', 'Update Company'])
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
                    @if (isFeatureEnabled('Pad Management'))
                        @can('Read Pad')
                            <li>
                                <x-common.button
                                    tag="a"
                                    href="{{ route('pads.index') }}"
                                    label="Pads"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('pads.*') && !request()->routeIs('pads.transactions.*')? 'text-green has-text-weight-bold': '' }}"
                                    x-init="{{ request()->routeIs('pads.*') && !request()->routeIs('pads.transactions.*') ? 'activateAccordion' : '' }}"
                                />
                            </li>
                        @endcan
                    @endif
                    @if (isFeatureEnabled('User Management'))
                        @can('Read Employee')
                            <li>
                                <x-common.button
                                    tag="a"
                                    href="{{ route('employees.index') }}"
                                    label="Users"
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
                        @if ($pad->isEnabled())
                            {{-- @can('') --}}
                            <li>
                                <x-common.button
                                    tag="a"
                                    href="{{ route('pads.transactions.index', $pad->id) }}"
                                    label="{{ $pad->name }}"
                                    class="has-text-grey has-text-weight-normal is-size-6-5 {{ request()->routeIs('pads.transactions.*') && request()->route('pad')->id == $pad->id? 'text-green has-text-weight-bold': '' }}"
                                    x-init="{{ request()->routeIs('pads.transactions.*') && request()->route('pad')->id == $pad->id? 'activateAccordion': '' }}"
                                />
                            </li>
                            {{-- @endcan --}}
                        @endif
                    @endforeach
                </ul>
            </li>
        </ul>
    @endcanany
</aside>
