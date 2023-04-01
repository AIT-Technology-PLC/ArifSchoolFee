@extends('layouts.app')

@section('title', 'Main Menu')

@section('content')
    <x-common.content-wrapper>
        @if (isFeatureEnabled('Bill Of Material Management', 'Job Management'))
            @canany(['Read BOM', 'Read Job'])
                <section class="mb-5">
                    <x-content.header>
                        <x-slot name="header">
                            <span class="icon">
                                <i class="fas fa-industry"></i>
                            </span>
                            <span class="ml-2">
                                Production
                            </span>
                        </x-slot>
                    </x-content.header>
                    <x-content.footer>
                        <div class="columns is-marginless is-multiline is-mobile">
                            @if (isFeatureEnabled('Job Management'))
                                @can('Read Job')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('jobs.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-cogs"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Jobs
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Job Management'))
                                @can('Plan Job')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('job-planners.create') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-chart-bar"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Planner
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Bill Of Material Management'))
                                @can('Read BOM')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('bill-of-materials.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-clipboard-list"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Bill Of Materials
                                        </span>
                                    </div>
                                @endcan
                            @endif
                        </div>
                    </x-content.footer>
                </section>
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
                <section class="mb-5">
                    <x-content.header>
                        <x-slot name="header">
                            <span class="icon">
                                <i class="fas fa-users"></i>
                            </span>
                            <span class="ml-2">
                                Human Resource
                            </span>
                        </x-slot>
                    </x-content.header>
                    <x-content.footer>
                        <div class="columns is-marginless is-multiline is-mobile">
                            @if (isFeatureEnabled('Employee Management'))
                                @can('Read Employee')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('employees.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-users"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Employees
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Department Management'))
                                @can('Read Department')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('departments.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-users-rectangle"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Departments
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Attendance Management'))
                                @can('Read Attendance')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('attendances.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fa-solid fa-user-clock"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Attendances
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Employee Transfer'))
                                @can('Read Employee Transfer')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('employee-transfers.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-people-arrows-left-right"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Transfers
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Warning Management'))
                                @can('Read Warning')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('warnings.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-circle-exclamation"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Warnings
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Advancement Management'))
                                @can('Read Advancement')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('advancements.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-arrows-up-down"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Advancements
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Leave Management'))
                                @can('Read Leave')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('leave-categories.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fa-solid fa-umbrella-beach"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Leave Categories
                                        </span>
                                    </div>
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('leaves.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fa-solid fa-umbrella-beach"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Leaves
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Expense Claim'))
                                @can('Read Expense Claim')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('expense-claims.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fa-solid fa-file-invoice-dollar"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Expense Claims
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Announcement Management'))
                                @can('Read Announcement')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('announcements.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fa-solid fa-rss"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Announcements
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Compensation Management'))
                                @can('Read Compensation')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('compensations.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fa-solid fa-circle-dollar-to-slot"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Compensation
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Compensation Adjustment'))
                                @can('Read Compensation Adjustment')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('compensation-adjustments.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fa-solid fa-circle-dollar-to-slot"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Adjustments
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Payroll Management'))
                                @can('Read Payroll')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('payrolls.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fa-solid fa-coins"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Payrolls
                                        </span>
                                    </div>
                                @endcan
                            @endif
                        </div>
                    </x-content.footer>
                </section>
            @endcanany
        @endif

        @if (isFeatureEnabled('Merchandise Inventory', 'Warehouse Management', 'Grn Management', 'Transfer Management', 'Damage Management', 'Inventory Adjustment', 'Siv Management'))
            @canany(['Read Available Inventory', 'Read Warehouse', 'Read GRN', 'Read Transfer', 'Read Damage', 'Read Adjustment', 'Read SIV'])
                <section class="mb-5">
                    <x-content.header>
                        <x-slot name="header">
                            <span class="icon">
                                <i class="fas fa-warehouse"></i>
                            </span>
                            <span class="ml-2">
                                Warehouse & Inventory
                            </span>
                        </x-slot>
                    </x-content.header>
                    <x-content.footer>
                        <div class="columns is-marginless is-multiline is-mobile">
                            @if (isFeatureEnabled('Merchandise Inventory'))
                                @can('Read Available Inventory')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('merchandises.index', 'available') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-chart-bar"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Inventory Level
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Warehouse Management'))
                                @can('Read Warehouse')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('warehouses.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-warehouse"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Warehouses
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Grn Management'))
                                @can('Read GRN')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('grns.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-file-import"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            GRN
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Transfer Management'))
                                @can('Read Transfer')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('transfers.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-exchange-alt"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Transfers
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Damage Management'))
                                @can('Read Damage')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('damages.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-bolt"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Damages
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Inventory Adjustment'))
                                @can('Read Adjustment')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('adjustments.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-eraser"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Adjustments
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Siv Management'))
                                @can('Read SIV')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('sivs.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-file-export"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            SIV
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @foreach (pads('Warehouse & Inventory') as $pad)
                                @canpad('Read', $pad)
                                <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                    <a
                                        href="{{ route('pads.transactions.index', $pad->id) }}"
                                        class="general-menu-item button text-green bg-lightgreen is-borderless"
                                    >
                                        <span class="icon is-size-5">
                                            <i class="{{ $pad->icon }}"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-6 is-size-7-mobile text-green">
                                        {{ $pad->abbreviation }}
                                    </span>
                                </div>
                                @endcanpad
                            @endforeach
                        </div>
                    </x-content.footer>
                </section>
            @endcanany
        @endif

        @if (isFeatureEnabled('Sale Management', 'Gdn Management', 'Proforma Invoice', 'Reservation Management', 'Return Management', 'Customer Management', 'Contact Management'))
            @canany(['Read Sale', 'Read GDN', 'Read Proforma Invoice', 'Read Reservation', 'Read Return', 'Read Customer', 'Read Contact'])
                <section class="mb-5">
                    <x-content.header>
                        <x-slot name="header">
                            <span class="icon">
                                <i class="fas fa-tags"></i>
                            </span>
                            <span class="ml-2">
                                Sales & Customers
                            </span>
                        </x-slot>
                    </x-content.header>
                    <x-content.footer>
                        <div class="columns is-marginless is-multiline is-mobile">
                            @if (isFeatureEnabled('Sale Management'))
                                @can('Read Sale')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('sales.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-cash-register"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Invoices
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Gdn Management'))
                                @can('Read GDN')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('gdns.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-file-invoice"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Delivery Orders
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Proforma Invoice'))
                                @can('Read Proforma Invoice')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('proforma-invoices.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-receipt"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Proforma Invoices
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Reservation Management'))
                                @can('Read Reservation')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('reservations.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-archive"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Reservations
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Return Management'))
                                @can('Read Return')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('returns.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-arrow-alt-circle-left"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Returns
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Customer Management'))
                                @can('Read Customer')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('customers.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-address-card"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Customers
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Contact Management'))
                                @can('Read Contact')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('contacts.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-address-card"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Contacts
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @foreach (pads('Sales & Customers') as $pad)
                                @canpad('Read', $pad)
                                <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                    <a
                                        href="{{ route('pads.transactions.index', $pad->id) }}"
                                        class="general-menu-item button text-green bg-lightgreen is-borderless"
                                    >
                                        <span class="icon is-size-5">
                                            <i class="{{ $pad->icon }}"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-6 is-size-7-mobile text-green">
                                        {{ $pad->abbreviation }}
                                    </span>
                                </div>
                                @endcanpad
                            @endforeach
                        </div>
                    </x-content.footer>
                </section>
            @endcanany
        @endif

        @if (isFeatureEnabled('Tender Management'))
            @can('Read Tender')
                <section class="mb-5">
                    <x-content.header>
                        <x-slot name="header">
                            <span class="icon">
                                <i class="fas fa-project-diagram"></i>
                            </span>
                            <span class="ml-2">
                                Tenders
                            </span>
                        </x-slot>
                    </x-content.header>
                    <x-content.footer>
                        <div class="columns is-marginless is-multiline is-mobile">
                            <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                <a
                                    href="{{ route('tenders.index') }}"
                                    class="general-menu-item button text-green bg-lightgreen is-borderless"
                                >
                                    <span class="icon is-size-5">
                                        <i class="fas fa-project-diagram"></i>
                                    </span>
                                </a>
                                <br>
                                <span class="is-size-6 is-size-7-mobile text-green">
                                    Tenders
                                </span>
                            </div>
                            <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                <a
                                    href="{{ route('tender-opportunities.index') }}"
                                    class="general-menu-item button text-green bg-lightgreen is-borderless"
                                >
                                    <span class="icon is-size-5">
                                        <i class="fas fa-comment-dollar"></i>
                                    </span>
                                </a>
                                <br>
                                <span class="is-size-6 is-size-7-mobile text-green">
                                    Tender Opportunities
                                </span>
                            </div>
                            <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                <a
                                    href="{{ route('tender-checklist-types.index') }}"
                                    class="general-menu-item button text-green bg-lightgreen is-borderless"
                                >
                                    <span class="icon is-size-5">
                                        <i class="fas fa-tasks"></i>
                                    </span>
                                </a>
                                <br>
                                <span class="is-size-6 is-size-7-mobile text-green">
                                    Checklist Categories
                                </span>
                            </div>
                            <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                <a
                                    href="{{ route('general-tender-checklists.index') }}"
                                    class="general-menu-item button text-green bg-lightgreen is-borderless"
                                >
                                    <span class="icon is-size-5">
                                        <i class="fas fa-check-double"></i>
                                    </span>
                                </a>
                                <br>
                                <span class="is-size-6 is-size-7-mobile text-green">
                                    Available Checklists
                                </span>
                            </div>
                            <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                <a
                                    href="{{ route('tender-statuses.index') }}"
                                    class="general-menu-item button text-green bg-lightgreen is-borderless"
                                >
                                    <span class="icon is-size-5">
                                        <i class="fas fa-info"></i>
                                    </span>
                                </a>
                                <br>
                                <span class="is-size-6 is-size-7-mobile text-green">
                                    Statuses
                                </span>
                            </div>
                        </div>
                    </x-content.footer>
                </section>
            @endcan
        @endif

        @if (isFeatureEnabled('Product Management', 'Price Management', 'Price Increment'))
            @can('Read Product', 'Read Price', 'Read Price Increment')
                <section class="mb-5">
                    <x-content.header>
                        <x-slot name="header">
                            <span class="icon">
                                <i class="fas fa-th"></i>
                            </span>
                            <span class="ml-2">
                                Products & Categories
                            </span>
                        </x-slot>
                    </x-content.header>
                    <x-content.footer>
                        <div class="columns is-marginless is-multiline is-mobile">
                            <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                <a
                                    href="{{ route('categories.index') }}"
                                    class="general-menu-item button text-green bg-lightgreen is-borderless"
                                >
                                    <span class="icon is-size-5">
                                        <i class="fas fa-layer-group"></i>
                                    </span>
                                </a>
                                <br>
                                <span class="is-size-6 is-size-7-mobile text-green">
                                    Categories
                                </span>
                            </div>
                            <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                <a
                                    href="{{ route('products.index') }}"
                                    class="general-menu-item button text-green bg-lightgreen is-borderless"
                                >
                                    <span class="icon is-size-5">
                                        <i class="fas fa-th"></i>
                                    </span>
                                </a>
                                <br>
                                <span class="is-size-6 is-size-7-mobile text-green">
                                    Products
                                </span>
                            </div>
                            @if (isFeatureEnabled('Price Management'))
                                @can('Read Price')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('prices.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-tags"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Prices
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Price Increment'))
                                @can('Read Price Increment')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('price-increments.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-tags"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Price Increments
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Brand Management'))
                                @can('Read Brand')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('brands.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-trademark"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Brands
                                        </span>
                                    </div>
                                @endcan
                            @endif
                        </div>
                    </x-content.footer>
                </section>
            @endcan
        @endif

        @if (isFeatureEnabled('Purchase Management', 'Supplier Management', 'Debt Management', 'Credit Management', 'Expense Management', 'Customer Deposit Management'))
            @canany(['Read Purchase', 'Read Supplier', 'Read Debt', 'Read Credit', 'Read Expense', 'Read Customer Deposit'])
                <section class="mb-5">
                    <x-content.header>
                        <x-slot name="header">
                            <span class="icon">
                                <i class="fas fa-chart-line"></i>
                            </span>
                            <span class="ml-2">
                                Finance
                            </span>
                        </x-slot>
                    </x-content.header>
                    <x-content.footer>
                        <div class="columns is-marginless is-multiline is-mobile">
                            @if (isFeatureEnabled('Purchase Management'))
                                @can('Read Purchase')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('purchases.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-shopping-bag"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Purchases
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Supplier Management'))
                                @can('Read Supplier')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('suppliers.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-address-card"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Suppliers
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Debt Management'))
                                @can('Read Debt')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('debts.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-money-check-dollar"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Debts
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Credit Management'))
                                @can('Read Credit')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('credits.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-money-check"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Credits
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Customer Deposit Management'))
                                @can('Read Customer Deposit')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('customer-deposits.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fa-solid fa-sack-dollar"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Deposits
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Expense Management'))
                                @can('Read Expense')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('expense-categories.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fa-solid fa-money-bill-trend-up"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Expense Categories
                                        </span>
                                    </div>
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('expenses.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fa-solid fa-money-bill-trend-up"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Expenses
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @foreach (pads('Purchase & Suppliers') as $pad)
                                @canpad('Read', $pad)
                                <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                    <a
                                        href="{{ route('pads.transactions.index', $pad->id) }}"
                                        class="general-menu-item button text-green bg-lightgreen is-borderless"
                                    >
                                        <span class="icon is-size-5">
                                            <i class="{{ $pad->icon }}"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-6 is-size-7-mobile text-green">
                                        {{ $pad->abbreviation }}
                                    </span>
                                </div>
                                @endcanpad
                            @endforeach
                        </div>
                    </x-content.footer>
                </section>
            @endcanany
        @endif

        @if (isFeatureEnabled('Sales Report', 'Return Report', 'Expense Report', 'Customer Report', 'Daily Inventory Level Report', 'Credit Management', 'Debt Management', 'Inventory Transfer Report', 'Credit Report'))
            @canany(['Read Sale Report', 'Read Return Report', 'Read Expense Report', 'Read Customer Report', 'Read Daily Inventory Report', 'Read Credit', 'Read Debt', 'Read Inventory Transfer Report', 'Read Credit Report'])
                <section class="mb-5">
                    <x-content.header>
                        <x-slot name="header">
                            <span class="icon">
                                <i class="fas fa-chart-pie"></i>
                            </span>
                            <span class="ml-2">
                                Report & Analytics
                            </span>
                        </x-slot>
                    </x-content.header>
                    <x-content.footer>
                        <div class="columns is-marginless is-multiline is-mobile">
                            @if (isFeatureEnabled('Sales Report'))
                                @can('Read Sale Report')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('reports.sale') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-file-lines"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Sales
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Return Report'))
                                @can('Read Return Report')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('reports.return') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fa-solid fa-file-circle-xmark"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Sales Return
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Expense Report'))
                                @can('Read Expense Report')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('reports.expense') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-chart-column"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Expense
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Customer Report'))
                                @can('Read Customer Report')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('reports.customer') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-chalkboard-user"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Customers
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Daily Inventory Level Report'))
                                @can('Read Daily Inventory Report')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('reports.inventory_level') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-warehouse"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Daily Inventory Level
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Inventory Transfer Report'))
                                @can('Read Inventory Transfer Report')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('reports.inventory_transfer') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-chart-bar"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Inventory Transfer
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Credit Report'))
                                @can('Read Credit Report')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('reports.credit') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-hand-holding-dollar"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Credit
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Credit Management'))
                                @can('Read Credit')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('receivables.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-hand-holding-dollar"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Receivables & Aging
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('Debt Management'))
                                @can('Read Debt')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('payables.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-sack-dollar"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Payables & Aging
                                        </span>
                                    </div>
                                @endcan
                            @endif
                        </div>
                    </x-content.footer>
                </section>
            @endcanany
        @endif

        @if (isFeatureEnabled('Pad Management', 'User Management', 'General Settings'))
            @canany(['Read Pad', 'Read Employee', 'Update Company'])
                <section>
                    <x-content.header>
                        <x-slot name="header">
                            <span class="icon">
                                <i class="fas fa-cog"></i>
                            </span>
                            <span class="ml-2">
                                Settings
                            </span>
                        </x-slot>
                    </x-content.header>
                    <x-content.footer>
                        <div class="columns is-marginless is-multiline is-mobile">
                            @if (isFeatureEnabled('Pad Management'))
                                @can('Read Pad')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('pads.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-book"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Pads
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('User Management') && !isFeatureEnabled('Employee Management'))
                                @can('Read Employee')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('employees.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-users"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Employees
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('General Settings'))
                                @can('Update Company')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('companies.edit', userCompany()->id) }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-building"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Company Profile
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @foreach (pads('General Settings') as $pad)
                                @canpad('Read', $pad)
                                <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                    <a
                                        href="{{ route('pads.transactions.index', $pad->id) }}"
                                        class="general-menu-item button text-green bg-lightgreen is-borderless"
                                    >
                                        <span class="icon is-size-5">
                                            <i class="{{ $pad->icon }}"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-6 is-size-7-mobile text-green">
                                        {{ $pad->abbreviation }}
                                    </span>
                                </div>
                                @endcanpad
                            @endforeach
                        </div>
                    </x-content.footer>
                </section>
            @endcanany
        @endif
    </x-common.content-wrapper>
@endsection
