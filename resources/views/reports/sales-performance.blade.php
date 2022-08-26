@extends('layouts.app')

@section('title', 'Sales Performance')

@section('content')
    <x-common.report-filter>
        <div class="column is-3 pt-0">
            <x-forms.field class="has-text-centered">
                <x-forms.control>
                    <x-forms.select
                        id="branch"
                        name="branch"
                        class="is-size-7-mobile is-fullwidth"
                    >
                        <option disabled> Branches </option>
                        <option
                            value=""
                            @selected(request('branch') == '')
                        > All </option>
                        @foreach ($warehouses as $warehouse)
                            <option
                                value="{{ $warehouse->id }}"
                                @selected(request('branch') == $warehouse->id)
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
    </x-common.report-filter>

    <div class="columns is-marginless is-multiline mt-3">
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($revenueReport->getTotalRevenueBeforeTax(), 2)"
                border-color="#fff"
                text-color="text-green"
                label="Revenue Before VAT"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($revenueReport->getTotalRevenueAfterTax(), 2)"
                border-color="#fff"
                text-color="text-purple"
                label="Revenue After VAT"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($revenueReport->getTotalRevenueTax(), 2)"
                border-color="#fff"
                text-color="text-gold"
                label="Revenue VAT"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($revenueReport->getDailyAverageRevenue(), 2)"
                border-color="#fff"
                text-color="text-blue"
                label="Daily Average Revenue"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($revenueReport->getTotalRevenueReveivables(), 2)"
                border-color="#fff"
                text-color="text-blue"
                label="Receivable Revenue"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($transactionReport->getAverageTransactionValue(), 2)"
                border-color="#fff"
                text-color="text-gold"
                label="Average Transaction Value"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="$transactionReport->getTotalTransactionCount()"
                border-color="#fff"
                text-color="text-purple"
                label="Transaction"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($transactionReport->getAverageItemsPerTransaction(), 2)"
                border-color="#fff"
                text-color="text-green"
                label="Basket Size Analysis"
            />
        </div>
        <div class="column is-6 p-lr-0">
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-green has-text-weight-medium is-size-6">
                        Top Customers by Revenue
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
                        @foreach ($revenueReport->getCustomersByRevenue() as $customerRevenue)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $customerRevenue['customer'] }} </td>
                                <td class="has-text-right"> {{ number_format($customerRevenue['revenue'], 2) }} </td>
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
                        Top Performing Branches
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
                        <th><abbr> Branch </abbr></th>
                        <th class="has-text-right"><abbr> Revenue </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($revenueReport->getBranchesByRevenue() as $branchRevenue)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $branchRevenue['branch'] }} </td>
                                <td class="has-text-right"> {{ number_format($branchRevenue['revenue'], 2) }} </td>
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
                        Salesperson Leaderboard
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
                        <th><abbr> Salesperson </abbr></th>
                        <th class="has-text-right"><abbr> Revenue </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($revenueReport->getRepsByRevenue() as $salesRevenue)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $salesRevenue['sales'] }} </td>
                                <td class="has-text-right"> {{ number_format($salesRevenue['revenue'], 2) }} </td>
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
                        Best-Selling Products
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
                        <th><abbr> Product </abbr></th>
                        <th><abbr> Quantity </abbr></th>
                        <th class="has-text-right"><abbr> Revenue </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($revenueReport->getProductsByRevenue() as $productRevenue)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $productRevenue['product'] }} </td>
                                <td> {{ $productRevenue['quantity'] }} </td>
                                <td class="has-text-right"> {{ number_format($productRevenue['revenue'], 2) }} </td>
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
                        Best Performing Categories
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
                        <th><abbr> Category </abbr></th>
                        <th><abbr> Quantity </abbr></th>
                        <th class="has-text-right"><abbr> Revenue </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($revenueReport->getProductCategoriesByRevenue() as $categoryRevenue)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $categoryRevenue['category'] }} </td>
                                <td> {{ $categoryRevenue['quantity'] }} </td>
                                <td class="has-text-right"> {{ number_format($categoryRevenue['revenue'], 2) }} </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>
    </div>
@endsection
