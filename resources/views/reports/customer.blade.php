@extends('layouts.app')

@section('title', 'Customers Report')

@section('content')
    <x-common.fail-message :message="session('failedMessage')" />

    <x-common.report-filter action="{{ route('reports.customer') }}">
        <div class="quickview-body">
            <div class="quickview-block">
                <div class="columns is-marginless is-vcentered is-multiline is-mobile">
                    <div class="column is-12">
                        <x-forms.label>
                            Period
                        </x-forms.label>
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.input
                                    type="text"
                                    id="period"
                                    name="period"
                                    class="is-size-7-mobile is-fullwidth has-text-centered"
                                    value="{{ request('period') }}"
                                />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label>
                            Branch
                        </x-forms.label>
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id="branches"
                                    name="branches"
                                    class="is-size-7-mobile is-fullwidth"
                                >
                                    <option disabled> Branches </option>
                                    <option
                                        value=""
                                        @selected(request('branches') == '')
                                    > All </option>
                                    @foreach ($warehouses as $warehouse)
                                        <option
                                            value="{{ $warehouse->id }}"
                                            @selected(request('branches') == $warehouse->id)
                                        > {{ $warehouse->name }} </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </div>
        </div>
    </x-common.report-filter>

    <h1 class="mx-3 m-lr-0 mt-5 text-green has-text-weight-medium is-size-6-mobile">
        <span class="icon">
            <i class="fas fa-user"></i>
        </span>
        <span>
            Customers Summary
        </span>
    </h1>

    <div class="columns is-marginless is-multiline">
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($customerReport->getTotalCustomers)"
                border-color="#fff"
                text-color="text-green"
                label="Total Customers"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($customerReport->getTotalActiveCustomers)"
                border-color="#fff"
                text-color="text-purple"
                label="Active Customers"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($customerReport->getTotalInactiveCustomers)"
                border-color="#fff"
                text-color="text-gold"
                label="Inactive Customers"
            />
        </div>
    </div>

    <h1 class="mx-3 m-lr-0 mt-5 text-green has-text-weight-medium is-size-6-mobile">
        <span class="icon">
            <i class="fas fa-filter"></i>
        </span>
        <span>
            Filtered Customers Report
        </span>
    </h1>

    <div class="columns is-marginless is-multiline">
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($customerReport->getTotalNewCustomers)"
                border-color="#fff"
                text-color="text-green"
                label="New Customers"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                amount="{{ number_format($customerReport->getTotalRetainedCustomers['amount']) }} ({{ number_format($customerReport->getTotalRetainedCustomers['percent'], 2) }}%)"
                border-color="#fff"
                text-color="text-purple"
                label="Retained Customers"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                amount="{{ number_format($customerReport->getTotalChurnedCustomers['amount']) }} ({{ number_format($customerReport->getTotalChurnedCustomers['percent'], 2) }}%)"
                border-color="#fff"
                text-color="text-gold"
                label="Churned Customers"
            />
        </div>
        <div class="column is-6 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($customerReport->getAverageRevenuePerCustomer, 2)"
                border-color="#fff"
                text-color="text-green"
                label="Average Revenue Per Customer"
            />
        </div>
        <div class="column is-6 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($customerReport->getAverageSalesTransactionsPerCustomer)"
                border-color="#fff"
                text-color="text-purple"
                label="Average Sales Transactions Per Customer"
            />
        </div>
        <div class="column is-6 p-lr-0">
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-green has-text-weight-medium is-size-6">
                        <span class="icon mr-1">
                            <i class="fas fa-user"></i>
                        </span>
                        <span>Top Customers by Revenue</span>
                    </h1>
                </x-slot:header>
            </x-content.header>
            <x-content.footer>
                <x-common.client-datatable
                    has-filter="false"
                    has-length-change="false"
                    paging-type="simple"
                    length-menu=[5]
                >
                    <x-slot name="headings">
                        <th><abbr> # </abbr></th>
                        <th><abbr> Customer </abbr></th>
                        <th class="has-text-right"><abbr> Revenue </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($customerReport->saleReport->getCustomersByRevenue as $customerRevenue)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $customerRevenue->customer_name ?? 'N/A' }} </td>
                                <td class="has-text-right"> {{ number_format($customerRevenue->revenue, 2) }} </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>
        <div class="column is-6 p-lr-0">
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-green has-text-weight-medium is-size-6">
                        <span class="icon mr-1">
                            <i class="fas fa-cash-register"></i>
                        </span>
                        <span>Top Customers by Sales</span>
                    </h1>
                </x-slot:header>
            </x-content.header>
            <x-content.footer>
                <x-common.client-datatable
                    has-filter="false"
                    has-length-change="false"
                    paging-type="simple"
                    length-menu=[5]
                >
                    <x-slot name="headings">
                        <th><abbr> # </abbr></th>
                        <th><abbr> Customer </abbr></th>
                        <th class="has-text-right"><abbr> Transactions </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($customerReport->getCustomersBySalesTransactionsCount as $customerSales)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $customerSales->customer_name ?? 'N/A' }} </td>
                                <td class="has-text-right"> {{ $customerSales->transactions }} </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>
        <div class="column is-6 p-lr-0">
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-green has-text-weight-medium is-size-6">
                        <span class="icon mr-1">
                            <i class="fas fa-credit-card"></i>
                        </span>
                        <span>Customers Payment Method Preferences</span>
                    </h1>
                </x-slot:header>
            </x-content.header>
            <x-content.footer>
                <x-common.client-datatable
                    has-filter="false"
                    has-length-change="false"
                    paging-type="simple"
                    length-menu=[5]
                >
                    <x-slot name="headings">
                        <th><abbr> # </abbr></th>
                        <th><abbr> Customer </abbr></th>
                        <th class="has-text-right"><abbr> Cash Payment </abbr></th>
                        <th class="has-text-right"><abbr> Credit Payment </abbr></th>
                        <th class="has-text-right"><abbr> Bank Deposit </abbr></th>
                        <th class="has-text-right"><abbr> Bank Transfer </abbr></th>
                        <th class="has-text-right"><abbr> Cheque </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($customerReport->getCustomersByPaymentMethod as $paymentMethod)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $paymentMethod->customer_name }} </td>
                                <td class="has-text-right"> {{ $paymentMethod->cash_payment }} </td>
                                <td class="has-text-right"> {{ $paymentMethod->credit_payment }} </td>
                                <td class="has-text-right"> {{ $paymentMethod->bank_deposit }} </td>
                                <td class="has-text-right"> {{ $paymentMethod->bank_transfer }} </td>
                                <td class="has-text-right"> {{ $paymentMethod->cheque }} </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>
        <div class="column is-6 p-lr-0">
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-green has-text-weight-medium is-size-6">
                        <span class="icon mr-1">
                            <i class="fas fa-credit-card"></i>
                        </span>
                        <span>Most Used Payment Methods</span>
                    </h1>
                </x-slot:header>
            </x-content.header>
            <x-content.footer>
                <x-common.client-datatable
                    has-filter="false"
                    has-length-change="false"
                    paging-type="simple"
                    length-menu=[5]
                >
                    <x-slot name="headings">
                        <th><abbr> # </abbr></th>
                        <th><abbr> Payment Method </abbr></th>
                        <th class="has-text-right"><abbr> Transactions </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($customerReport->saleReport->getPaymentTypesByRevenue as $paymentMethod)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $paymentMethod->payment_type }} </td>
                                <td class="has-text-right"> {{ $paymentMethod->transactions }} </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>
    </div>
@endsection
