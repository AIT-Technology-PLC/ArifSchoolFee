@extends('layouts.app')

@section('title', 'Customer Report')

@section('content')
    <x-common.report-filter action="{{ route('reports.customer') }}">
        <div class="columns is-marginless is-vcentered">
            <div class="column is-3 p-lr-0 pt-0">
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
            <div class="column is-3 p-lr-0 pt-0">
                <x-forms.field class="has-text-centered">
                    <x-forms.control>
                        <x-forms.input
                            type="text"
                            id="period"
                            name="period"
                            class="is-size-7-mobile is-fullwidth"
                            value="{{ request('period') }}"
                        />
                    </x-forms.control>
                </x-forms.field>
            </div>
        </div>
    </x-common.report-filter>

    <div class="columns is-marginless is-multiline mt-3">
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="$customerReport->getTotalCustomers"
                border-color="#fff"
                text-color="text-gold"
                label="Total"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="$customerReport->getTotalNewCustomers"
                border-color="#fff"
                text-color="text-purple"
                label="New"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                amount="{{ $revenueReport->getTotalRetainedCustomers }} %"
                border-color="#fff"
                text-color="text-green"
                label="Retained"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                amount="{{ $revenueReport->getTotalChurnedCustomers }} %"
                border-color="#fff"
                text-color="text-blue"
                label="Churned"
            />
        </div>
        <div class="column is-6 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($revenueReport->getAverageRevenuePerCustomer, 2)"
                border-color="#fff"
                text-color="text-gold"
                label="Average Revenue Per Customer"
            />
        </div>
        <div class="column is-6 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="$revenueReport->getAverageSalesTransactionsPerCustomer"
                border-color="#fff"
                text-color="text-green"
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
                        @foreach ($revenueReport->getCustomersByRevenue as $customerRevenue)
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
                        <th class="has-text-right"><abbr> Bought It </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($revenueReport->getCustomersBySalesTransactionsCount as $customerSales)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $customerSales->customer_name ?? 'N/A' }} </td>
                                <td class="has-text-right"> {{ $customerSales->transactions }} times </td>
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
                        <span>Customers By Payment Method</span>
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
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($revenueReport->getCustomerByPaymentMethod as $paymentMethod)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $paymentMethod->customer_name }} </td>
                                <td class="has-text-right"> {{ $paymentMethod->cash_payment }} </td>
                                <td class="has-text-right"> {{ $paymentMethod->credit_payment }} </td>
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
                            <i class="fas fa-shopping-cart"></i>
                        </span>
                        <span>Top Customers By Purchase Frequency</span>
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
                        <th class="has-text-right"><abbr> Purchase Frequency </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($revenueReport->getCustomersByPurchaseFrequency as $purchaseFrequency)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $purchaseFrequency->customer_name }} </td>
                                <td class="has-text-right"> {{ $purchaseFrequency->purchase_frequency }} {{ str()->plural('day', $purchaseFrequency->purchase_frequency) }} </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>
    </div>
@endsection
