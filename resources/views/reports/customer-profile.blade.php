@extends('layouts.app')

@section('title', str($customer->company_name)->title() . ' Profile')

@section('content')
    <h1 class="mx-3 m-lr-0 mb-4 text-green has-text-weight-bold is-size-5 is-size-6-mobile is-uppercase has-text-centered">
        <span class="icon">
            <i class="fas fa-user"></i>
        </span>
        <span>
            {{ $customer->company_name }} Profile
        </span>
    </h1>

    <x-common.report-filter action="{{ route('reports.profile', $customer->id) }}">
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
            <i class="fas fa-calendar"></i>
        </span>
        <span>
            Lifetime Sales Summary
        </span>
    </h1>

    <div class="columns is-marginless is-multiline">
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($lifetimeSalesReport->getTotalRevenueAfterTax, 2)"
                border-color="#fff"
                text-color="text-green"
                label="Revenue (After Tax)"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="$lifetimeSalesReport->getSalesCount"
                border-color="#fff"
                text-color="text-purple"
                label="Number of Sales"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($lifetimeSalesReport->getAverageItemsPerSale)"
                border-color="#fff"
                text-color="text-gold"
                label="Basket Size Analysis"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($totalCreditAmountProvided, 2)"
                border-color="#fff"
                text-color="text-blue"
                label="Credit Amount"
            />
        </div>
    </div>

    <h1 class="mx-3 m-lr-0 mt-5 text-green has-text-weight-medium is-size-6-mobile">
        <span class="icon">
            <i class="fas fa-credit-card"></i>
        </span>
        <span>
            Credit Summary
        </span>
    </h1>

    <div class="columns is-marginless is-multiline">
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($customer->credit_amount_limit, 2)"
                border-color="#fff"
                text-color="text-green"
                label="Credit Limit"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($currentCreditLimit, 2)"
                border-color="#fff"
                text-color="text-purple"
                label="Current Credit Limit"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($currentCreditBalance, 2)"
                border-color="#fff"
                text-color="text-gold"
                label="Unsettled Credit Amount"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                amount="{{ number_format($averageCreditSettlementDays, 2) }} Days"
                border-color="#fff"
                text-color="text-blue"
                label="Credit Settlement Duration"
            />
        </div>
    </div>

    <h1 class="mx-3 m-lr-0 mt-5 text-green has-text-weight-medium is-size-6-mobile">
        <span class="icon">
            <i class="fa-solid fa-sack-dollar"></i>
        </span>
        <span>
            Deposit Summary
        </span>
    </h1>

    <div class="columns is-marginless is-multiline">
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="$depositsCount"
                border-color="#fff"
                text-color="text-green"
                label="Number of Deposits"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($depositToDate, 2)"
                border-color="#fff"
                text-color="text-purple"
                label="Deposit To Date"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($currentDepositBalance, 2)"
                border-color="#fff"
                text-color="text-gold"
                label="Available Deposit"
            />
        </div>
    </div>

    <h1 class="mx-3 m-lr-0 mt-5 text-green has-text-weight-medium is-size-6-mobile">
        <span class="icon">
            <i class="fas fa-filter"></i>
        </span>
        <span>
            Filtered Sales Report
        </span>
    </h1>

    <div class="columns is-marginless is-multiline">
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($saleReport->getTotalRevenueAfterTax, 2)"
                border-color="#fff"
                text-color="text-green"
                label="Revenue (After Tax)"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="$saleReport->getSalesCount"
                border-color="#fff"
                text-color="text-purple"
                label="Number of Sales"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($saleReport->getAverageSaleValue, 2)"
                border-color="#fff"
                text-color="text-gold"
                label="Average Sale Value"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($saleReport->getAverageItemsPerSale)"
                border-color="#fff"
                text-color="text-blue"
                label="Basket Size Analysis"
            />
        </div>
    </div>

    <div class="columns is-marginless is-multiline">
        <div class="column is-6 p-lr-0">
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-green has-text-weight-medium is-size-6">
                        <span class="icon mr-1">
                            <i class="fas fa-warehouse"></i>
                        </span>
                        <span>Top Performing Branches</span>
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
                        @foreach ($saleReport->getBranchesByRevenue as $branchRevenue)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $branchRevenue->branch_name }} </td>
                                <td class="has-text-right"> {{ number_format($branchRevenue->revenue, 2) }} </td>
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
                            <i class="fas fa-user-tie"></i>
                        </span>
                        <span>Salesperson Leaderboard</span>
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
                        @foreach ($saleReport->getRepsByRevenue as $salesRevenue)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $salesRevenue->user_name ?? 'Deleted Salesperson' }} </td>
                                <td class="has-text-right"> {{ number_format($salesRevenue->revenue, 2) }} </td>
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
                            <i class="fas fa-th"></i>
                        </span>
                        <span>Best-Selling Products</span>
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
                        <th class="has-text-right"><abbr> Quantity </abbr></th>
                        <th class="has-text-right"><abbr> Revenue </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($saleReport->getProductsByRevenue as $productRevenue)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $productRevenue->product_name }} </td>
                                <td class="has-text-right"> {{ quantity($productRevenue->quantity, $productRevenue->product_unit_of_measurement) }} </td>
                                <td class="has-text-right"> {{ number_format($productRevenue->revenue, 2) }} </td>
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
                            <i class="fas fa-layer-group"></i>
                        </span>
                        <span>Best Performing Categories</span>
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
                        <th class="has-text-right"><abbr> Quantity </abbr></th>
                        <th class="has-text-right"><abbr> Revenue </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($saleReport->getProductCategoriesByRevenue as $categoryRevenue)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $categoryRevenue->product_category_name }} </td>
                                <td class="has-text-right"> {{ number_format($categoryRevenue->quantity, 2) }} </td>
                                <td class="has-text-right"> {{ number_format($categoryRevenue->revenue, 2) }} </td>
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
                        <span>Sales by Payment Methods</span>
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
                        <th class="has-text-right"><abbr> Revenue </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($saleReport->getPaymentTypesByRevenue as $paymentTypeRevenue)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $paymentTypeRevenue->payment_type }} </td>
                                <td class="has-text-right"> {{ $paymentTypeRevenue->transactions }} </td>
                                <td class="has-text-right"> {{ number_format($paymentTypeRevenue->revenue, 2) }} </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>
    </div>
@endsection
