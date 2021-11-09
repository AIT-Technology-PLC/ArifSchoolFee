<div
    x-data="toggler"
    @open-create-modal.window="toggle"
    class="modal"
    :class="{ 'is-active': !isHidden }"
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
                        @can('Create Warehouse')
                            @if (isFeatureEnabled('Warehouse Management'))
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
                            @endif
                        @endcan

                        @can('Create GRN')
                            @if (isFeatureEnabled('Grn Management'))
                                <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                    <x-common.button
                                        tag="a"
                                        mode="button"
                                        href="{{ route('grns.create') }}"
                                        icon="fas fa-file-signature"
                                        class="text-green bg-lightgreen is-borderless"
                                    />
                                    <br>
                                    <span class="is-size-7"> New GRN </span>
                                </div>
                            @endif
                        @endcan

                        @can('Create Transfer')
                            @if (isFeatureEnabled('Transfer Management'))
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
                            @endif
                        @endcan

                        @can('Create Damage')
                            @if (isFeatureEnabled('Damage Management'))
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
                            @endif
                        @endcan

                        @can('Create Adjustment')
                            @if (isFeatureEnabled('Inventory Adjustment'))
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
                            @endif
                        @endcan

                        @can('Create SIV')
                            @if (isFeatureEnabled('Siv Management'))
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
                            @endif
                        @endcan
                    </div>
                </x-content.footer>
            @endcanany

            @canany(['Create Sale', 'Create GDN', 'Create Proforma Invoice', 'Create Reservation', 'Create Return', 'Create PO', 'Create Customer'])
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
                        @can('Create Sale')
                            @if (isFeatureEnabled('Sale Management'))
                                <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                    <x-common.button
                                        tag="a"
                                        mode="button"
                                        href="{{ route('sales.create') }}"
                                        icon="fas fa-tags"
                                        class="text-green bg-lightgreen is-borderless"
                                    />
                                    <br>
                                    <span class="is-size-7"> New Invoice </span>
                                </div>
                            @endif
                        @endcan

                        @can('Create GDN')
                            @if (isFeatureEnabled('Gdn Management'))
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
                            @endif
                        @endcan

                        @can('Create Proforma Invoice')
                            @if (isFeatureEnabled('Proforma Invoice'))
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
                            @endif
                        @endcan

                        @can('Create Reservation')
                            @if (isFeatureEnabled('Reservation Management'))
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
                            @endif
                        @endcan

                        @can('Create Return')
                            @if (isFeatureEnabled('Return Management'))
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
                            @endif
                        @endcan

                        @can('Create PO')
                            @if (isFeatureEnabled('Purchase Order'))
                                <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                    <x-common.button
                                        tag="a"
                                        mode="button"
                                        href="{{ route('purchase-orders.create') }}"
                                        icon="fas fa-file-alt"
                                        class="text-green bg-lightgreen is-borderless"
                                    />
                                    <br>
                                    <span class="is-size-7"> New Purchase Order </span>
                                    </span>
                                </div>
                            @endif
                        @endcan

                        @can('Create Credit')
                            @if (isFeatureEnabled('Credit Management'))
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
                            @endif
                        @endcan

                        @can('Create Customer')
                            @if (isFeatureEnabled('Customer Management'))
                                <div class="column is-3-tablet is-4-mobile has-text-centered text-green">
                                    <x-common.button
                                        tag="a"
                                        mode="button"
                                        href="{{ route('customers.create') }}"
                                        icon="fas fa-user"
                                        class="text-green bg-lightgreen is-borderless"
                                    />
                                    <br>
                                    <span class="is-size-7"> New Customer </span>
                                </div>
                            @endif
                        @endcan
                    </div>
                </x-content.footer>
            @endcanany

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
                        @can('Create Tender')
                            @if (isFeatureEnabled('Tender Management'))
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
                            @endif
                        @endcan
                    </div>
                </x-content.footer>
            @endcan

            @canany(['Create Purchase', 'Create Supplier'])
                <x-content.header>
                    <x-slot name="header">
                        <x-common.icon
                            name="fas fa-shopping-bag"
                            class="is-size-6 text-green"
                        />
                        <span class="ml-2 is-size-6 text-green"> Purchases & Suppliers </span>
                    </x-slot>
                </x-content.header>
                <x-content.footer>
                    <div class="columns is-marginless is-multiline is-mobile">
                        @can('Create Purchase')
                            @if (isFeatureEnabled('Purchase Management'))
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
                            @endif
                        @endcan

                        @can('Create Supplier')
                            @if (isFeatureEnabled('Supplier Management'))
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
                            @endif
                        @endcan
                    </div>
                </x-content.footer>
            @endcanany

            @can('Create Product')
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
                        @can('Create Product')
                            @if (isFeatureEnabled('Product Management'))
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
                            @endif
                        @endcan
                    </div>
                </x-content.footer>
            @endcan

            @can('Create Employee')
                <x-content.header>
                    <x-slot name="header">
                        <x-common.icon
                            name="fas fa-users"
                            class="is-size-6 text-green"
                        />
                        <span class="ml-2 is-size-6 text-green"> Employees </span>
                    </x-slot>
                </x-content.header>
                <x-content.footer>
                    <div class="columns is-marginless is-multiline is-mobile">
                        @can('Create Employee')
                            @if (isFeatureEnabled('User Management'))
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
                            @endif
                        @endcan
                    </div>
                </x-content.footer>
            @endcan
        </div>
    </div>
    <x-common.button
        tag="button"
        class="modal-close is-large"
        @click="toggle"
    />
</div>
