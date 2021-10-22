<div
    id="menuModal"
    class="modal"
>
    <div
        name="createMenuModal"
        class="modal-background"
    ></div>
    <div class="modal-content p-lr-20">
        <div class="box is-radiusless">
            <h1 class="has-text-centered mb-3 is-uppercase text-purple has-text-weight-bold">
                <span class="icon">
                    <i class="fas fa-plus"></i>
                </span>
                <span>
                    Create New
                </span>
            </h1>

            @canany(['Create Merchandise', 'Create Warehouse', 'Create GRN', 'Create Transfer', 'Create Damage', 'Create Adjustment', 'Create SIV'])
                <div class="box has-background-white-bis">
                    <h2 class="mb-3 text-purple has-text-weight-bold">
                        <span>
                            Warehouse & Inventory
                        </span>
                    </h2>
                    <div class="columns is-marginless is-multiline is-mobile">
                        @can('Create Warehouse')
                            @if (isFeatureEnabled('Warehouse Management'))
                                <div class="column is-3-tablet is-4-mobile has-text-centered text-purple">
                                    <a
                                        href="{{ route('warehouses.create') }}"
                                        class="button text-purple bg-lightpurple is-borderless"
                                    >
                                        <span class="icon">
                                            <i class="fas fa-warehouse"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-7">
                                        <span>
                                            New Warehouse
                                        </span>
                                    </span>
                                </div>
                            @endif
                        @endcan

                        @can('Create GRN')
                            @if (isFeatureEnabled('Grn Management'))
                                <div class="column is-3-tablet is-4-mobile has-text-centered text-purple">
                                    <a
                                        href="{{ route('grns.create') }}"
                                        class="button text-purple bg-lightpurple is-borderless"
                                    >
                                        <span class="icon">
                                            <i class="fas fa-file-signature"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-7">
                                        <span>
                                            New GRN
                                        </span>
                                    </span>
                                </div>
                            @endif
                        @endcan

                        @can('Create Transfer')
                            @if (isFeatureEnabled('Transfer Management'))
                                <div class="column is-3-tablet is-4-mobile has-text-centered text-purple">
                                    <a
                                        href="{{ route('transfers.create') }}"
                                        class="button text-purple bg-lightpurple is-borderless"
                                    >
                                        <span class="icon">
                                            <i class="fas fa-exchange-alt"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-7">
                                        <span>
                                            New Transfer
                                        </span>
                                    </span>
                                </div>
                            @endif
                        @endcan

                        @can('Create Damage')
                            @if (isFeatureEnabled('Damage Management'))
                                <div class="column is-3-tablet is-4-mobile has-text-centered text-purple">
                                    <a
                                        href="{{ route('damages.create') }}"
                                        class="button text-purple bg-lightpurple is-borderless"
                                    >
                                        <span class="icon">
                                            <i class="fas fa-bolt"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-7">
                                        <span>
                                            New Damage
                                        </span>
                                    </span>
                                </div>
                            @endif
                        @endcan

                        @can('Create Adjustment')
                            @if (isFeatureEnabled('Inventory Adjustment'))
                                <div class="column is-3-tablet is-4-mobile has-text-centered text-purple">
                                    <a
                                        href="{{ route('adjustments.create') }}"
                                        class="button text-purple bg-lightpurple is-borderless"
                                    >
                                        <span class="icon">
                                            <i class="fas fa-eraser"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-7">
                                        <span>
                                            New Adjustment
                                        </span>
                                    </span>
                                </div>
                            @endif
                        @endcan

                        @can('Create SIV')
                            @if (isFeatureEnabled('Siv Management'))
                                <div class="column is-3-tablet is-4-mobile has-text-centered text-purple">
                                    <a
                                        href="{{ route('sivs.create') }}"
                                        class="button text-purple bg-lightpurple is-borderless"
                                    >
                                        <span class="icon">
                                            <i class="fas fa-file-export"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-7">
                                        <span>
                                            New SIV
                                        </span>
                                    </span>
                                </div>
                            @endif
                        @endcan
                    </div>
                </div>
            @endcanany

            @canany(['Create Sale', 'Create GDN', 'Create Proforma Invoice', 'Create Reservation', 'Create Return', 'Create PO', 'Create Customer'])
                <div class="box has-background-white-bis">
                    <h2 class="mb-3 text-purple has-text-weight-bold">
                        <span>
                            Sales & Customers
                        </span>
                    </h2>
                    <div class="columns is-marginless is-multiline is-mobile">
                        @can('Create Sale')
                            @if (isFeatureEnabled('Sale Management'))
                                <div class="column is-3-tablet is-4-mobile has-text-centered text-purple">
                                    <a
                                        href="{{ route('sales.create') }}"
                                        class="button text-purple bg-lightpurple is-borderless"
                                    >
                                        <span class="icon">
                                            <i class="fas fa-tags"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-7">
                                        <span>
                                            New Invoice
                                        </span>
                                    </span>
                                </div>
                            @endif
                        @endcan

                        @can('Create GDN')
                            @if (isFeatureEnabled('Gdn Management'))
                                <div class="column is-3-tablet is-4-mobile has-text-centered text-purple">
                                    <a
                                        href="{{ route('gdns.create') }}"
                                        class="button text-purple bg-lightpurple is-borderless"
                                    >
                                        <span class="icon">
                                            <i class="fas fa-file-invoice"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-7">
                                        <span>
                                            New DO
                                        </span>
                                    </span>
                                </div>
                            @endif
                        @endcan

                        @can('Create Proforma Invoice')
                            @if (isFeatureEnabled('Proforma Invoice'))
                                <div class="column is-3-tablet is-4-mobile has-text-centered text-purple">
                                    <a
                                        href="{{ route('proforma-invoices.create') }}"
                                        class="button text-purple bg-lightpurple is-borderless"
                                    >
                                        <span class="icon">
                                            <i class="fas fa-receipt"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-7">
                                        <span>
                                            New Proforma Invoices
                                        </span>
                                    </span>
                                </div>
                            @endif
                        @endcan

                        @can('Create Reservation')
                            @if (isFeatureEnabled('Reservation Management'))
                                <div class="column is-3-tablet is-4-mobile has-text-centered text-purple">
                                    <a
                                        href="{{ route('reservations.create') }}"
                                        class="button text-purple bg-lightpurple is-borderless"
                                    >
                                        <span class="icon">
                                            <i class="fas fa-archive"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-7">
                                        <span>
                                            New Reservation
                                        </span>
                                    </span>
                                </div>
                            @endif
                        @endcan

                        @can('Create Return')
                            @if (isFeatureEnabled('Return Management'))
                                <div class="column is-3-tablet is-4-mobile has-text-centered text-purple">
                                    <a
                                        href="{{ route('returns.create') }}"
                                        class="button text-purple bg-lightpurple is-borderless"
                                    >
                                        <span class="icon">
                                            <i class="fas fa-arrow-alt-circle-left"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-7">
                                        <span>
                                            New Return
                                        </span>
                                    </span>
                                </div>
                            @endif
                        @endcan

                        @can('Create PO')
                            @if (isFeatureEnabled('Purchase Order'))
                                <div class="column is-3-tablet is-4-mobile has-text-centered text-purple">
                                    <a
                                        href="{{ route('purchase-orders.create') }}"
                                        class="button text-purple bg-lightpurple is-borderless"
                                    >
                                        <span class="icon">
                                            <i class="fas fa-file-alt"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-7">
                                        <span>
                                            New Purchase Order
                                        </span>
                                    </span>
                                </div>
                            @endif
                        @endcan

                        @can('Create Customer')
                            @if (isFeatureEnabled('Customer Management'))
                                <div class="column is-3-tablet is-4-mobile has-text-centered text-purple">
                                    <a
                                        href="{{ route('customers.create') }}"
                                        class="button text-purple bg-lightpurple is-borderless"
                                    >
                                        <span class="icon">
                                            <i class="fas fa-user"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-7">
                                        <span>
                                            New Customer
                                        </span>
                                    </span>
                                </div>
                            @endif
                        @endcan
                    </div>
                </div>
            @endcanany

            @can('Create Tender')
                <div class="box has-background-white-bis">
                    <h2 class="mb-3 text-purple has-text-weight-bold">
                        <span>
                            Tenders
                        </span>
                    </h2>
                    <div class="columns is-marginless is-multiline is-mobile">
                        @can('Create Tender')
                            @if (isFeatureEnabled('Tender Management'))
                                <div class="column is-3-tablet is-4-mobile has-text-centered text-purple">
                                    <a
                                        href="{{ route('tenders.create') }}"
                                        class="button text-purple bg-lightpurple is-borderless"
                                    >
                                        <span class="icon">
                                            <i class="fas fa-project-diagram"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-7">
                                        <span>
                                            New Tender
                                        </span>
                                    </span>
                                </div>
                                <div class="column is-3-tablet is-4-mobile has-text-centered text-purple">
                                    <a
                                        href="{{ route('tender-checklist-types.create') }}"
                                        class="button text-purple bg-lightpurple is-borderless"
                                    >
                                        <span class="icon">
                                            <i class="fas fa-tasks"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-7">
                                        <span>
                                            New Checklist Category
                                        </span>
                                    </span>
                                </div>
                                <div class="column is-3-tablet is-4-mobile has-text-centered text-purple">
                                    <a
                                        href="{{ route('general-tender-checklists.create') }}"
                                        class="button text-purple bg-lightpurple is-borderless"
                                    >
                                        <span class="icon">
                                            <i class="fas fa-check-double"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-7">
                                        <span>
                                            New Checklist
                                        </span>
                                    </span>
                                </div>
                                <div class="column is-3-tablet is-4-mobile has-text-centered text-purple">
                                    <a
                                        href="{{ route('tender-statuses.create') }}"
                                        class="button text-purple bg-lightpurple is-borderless"
                                    >
                                        <span class="icon">
                                            <i class="fas fa-info"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-7">
                                        <span>
                                            New Status
                                        </span>
                                    </span>
                                </div>
                            @endif
                        @endcan
                    </div>
                </div>
            @endcan

            @canany(['Create Purchase', 'Create Supplier'])
                <div class="box has-background-white-bis">
                    <h2 class="mb-3 text-purple has-text-weight-bold">
                        <span>
                            Purchases & Suppliers
                        </span>
                    </h2>
                    <div class="columns is-marginless is-multiline is-mobile">
                        @can('Create Purchase')
                            @if (isFeatureEnabled('Purchase Management'))
                                <div class="column is-3-tablet is-4-mobile has-text-centered text-purple">
                                    <a
                                        href="{{ route('purchases.create') }}"
                                        class="button text-purple bg-lightpurple is-borderless"
                                    >
                                        <span class="icon">
                                            <i class="fas fa-shopping-bag"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-7">
                                        <span>
                                            New Purchase
                                        </span>
                                    </span>
                                </div>
                            @endif
                        @endcan

                        @can('Create Supplier')
                            @if (isFeatureEnabled('Supplier Management'))
                                <div class="column is-3-tablet is-4-mobile has-text-centered text-purple">
                                    <a
                                        href="{{ route('suppliers.create') }}"
                                        class="button text-purple bg-lightpurple is-borderless"
                                    >
                                        <span class="icon">
                                            <i class="fas fa-address-card"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-7">
                                        <span>
                                            New Supplier
                                        </span>
                                    </span>
                                </div>
                            @endif
                        @endcan
                    </div>
                </div>
            @endcanany

            @can('Create Product')
                <div class="box has-background-white-bis">
                    <h2 class="mb-3 text-purple has-text-weight-bold">
                        <span>
                            Products & Categories
                        </span>
                    </h2>
                    <div class="columns is-marginless is-multiline is-mobile">
                        @can('Create Product')
                            @if (isFeatureEnabled('Product Management'))
                                <div class="column is-3-tablet is-4-mobile has-text-centered text-purple">
                                    <a
                                        href="{{ route('categories.create') }}"
                                        class="button text-purple bg-lightpurple is-borderless"
                                    >
                                        <span class="icon">
                                            <i class="fas fa-layer-group"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-7">
                                        <span>
                                            New Category
                                        </span>
                                    </span>
                                </div>
                                <div class="column is-3-tablet is-4-mobile has-text-centered text-purple">
                                    <a
                                        href="{{ route('products.create') }}"
                                        class="button text-purple bg-lightpurple is-borderless"
                                    >
                                        <span class="icon">
                                            <i class="fas fa-th"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-7">
                                        <span>
                                            New Product
                                        </span>
                                    </span>
                                </div>
                            @endif
                        @endcan
                    </div>
                </div>
            @endcan

            @can('Create Employee')
                <div class="box has-background-white-bis">
                    <h2 class="mb-3 text-purple has-text-weight-bold">
                        <span>
                            Employees
                        </span>
                    </h2>
                    <div class="columns is-marginless is-multiline is-mobile">
                        @can('Create Employee')
                            @if (isFeatureEnabled('User Management'))
                                <div class="column is-3-tablet is-4-mobile has-text-centered text-purple">
                                    <a
                                        href="{{ route('employees.create') }}"
                                        class="button text-purple bg-lightpurple is-borderless"
                                    >
                                        <span class="icon">
                                            <i class="fas fa-users"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-7">
                                        <span>
                                            New Employee
                                        </span>
                                    </span>
                                </div>
                            @endif
                        @endcan
                    </div>
                </div>
            @endcan
        </div>
    </div>
    <button
        name="createMenuModal"
        class="modal-close is-large"
        aria-label="close"
    ></button>
</div>
