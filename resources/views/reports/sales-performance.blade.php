@extends('layouts.app')

@section('title', 'Sales Performance')

@section('content')
    <x-common.report-filter>
        <div class="column is-3 p-lr-0 pt-0">
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

    <div class="columns is-marginless is-multiline">
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                :amount="number_format($revenueReport->getTotalRevenueBeforeTax(), 2)"
                border-color="#3d8660"
                text-color="text-green"
                label="Total Revenue Before Tax"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                :amount="number_format($revenueReport->getTotalRevenueAfterTax(), 2)"
                border-color="#863d63"
                text-color="text-purple"
                label="Total Revenue After Tax"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                :amount="number_format($revenueReport->getTotalRevenueTax(), 2)"
                border-color="#86843d"
                text-color="text-gold"
                label="Total Revenue Tax"
            />
        </div>
        <div class="column is-6 p-lr-0">
            <x-common.index-insight
                :amount="number_format($revenueReport->getDailyAverageRevenue(), 2)"
                border-color="#3d6386"
                text-color="text-blue"
                label="Daily Average Revenue"
            />
        </div>
        <div class="column is-6 p-lr-0">
            <x-common.index-insight
                :amount="number_format($revenueReport->getTotalRevenueReveivables(), 2)"
                border-color="#3d6386"
                text-color="text-blue"
                label="Total Receivable Revenue"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                :amount="number_format($transactionReport->getAverageTransactionValue(), 2)"
                border-color="#86843d"
                text-color="text-gold"
                label="Average Transaction Value"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                :amount="$transactionReport->getTotalTransactionCount()"
                border-color="#863d63"
                text-color="text-purple"
                label="Total Transaction"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                :amount="number_format($transactionReport->getAverageItemsPerTransaction(), 2)"
                border-color="#3d8660"
                text-color="text-green"
                label="Basket Size Analysis"
            />
        </div>
        <div class="column is-6 p-lr-0">
            <x-content.header title="Top Customers by Revenue" />
            <x-content.footer>
                <x-common.client-datatable has-filter="false">
                    <x-slot name="headings">
                        <th><abbr> # </abbr></th>
                        <th><abbr> Customer </abbr></th>
                        <th><abbr> Revenue </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($revenueReport->getCustomersByRevenue() as $customerRevenue)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $customerRevenue['customer'] }} </td>
                                <td> {{ number_format($customerRevenue['revenue'], 2) }} </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>
        <div class="column is-6 p-lr-0">
            <x-content.header title="Top Performing Branches " />
            <x-content.footer>
                <x-common.client-datatable has-filter="false">
                    <x-slot name="headings">
                        <th><abbr> # </abbr></th>
                        <th><abbr> Branch </abbr></th>
                        <th><abbr> Revenue </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($revenueReport->getBranchesByRevenue() as $branchRevenue)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $branchRevenue['branch'] }} </td>
                                <td> {{ number_format($branchRevenue['revenue'], 2) }} </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>
        <div class="column is-6 p-lr-0">
            <x-content.header title="Salesperson Leaderboard" />
            <x-content.footer>
                <x-common.client-datatable has-filter="false">
                    <x-slot name="headings">
                        <th><abbr> # </abbr></th>
                        <th><abbr> Salesperson </abbr></th>
                        <th><abbr> Revenue </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($revenueReport->getRepsByRevenue() as $salesRevenue)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $salesRevenue['sales'] }} </td>
                                <td> {{ number_format($salesRevenue['revenue'], 2) }} </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>
        <div class="column is-6 p-lr-0">
            <x-content.header title="Best-Selling Products" />
            <x-content.footer>
                <x-common.client-datatable has-filter="false">
                    <x-slot name="headings">
                        <th><abbr> # </abbr></th>
                        <th><abbr> Product </abbr></th>
                        <th><abbr> Revenue </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($revenueReport->getProductsByRevenue() as $productRevenue)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $productRevenue['product'] }} </td>
                                <td> {{ number_format($productRevenue['revenue'], 2) }} </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>
        <div class="column is-6 p-lr-0">
            <x-content.header title="Best Performing Categories" />
            <x-content.footer>
                <x-common.client-datatable has-filter="false">
                    <x-slot name="headings">
                        <th><abbr> # </abbr></th>
                        <th><abbr> Category </abbr></th>
                        <th><abbr> Revenue </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($revenueReport->getProductCategoriesByRevenue() as $categoryRevenue)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $categoryRevenue['category'] }} </td>
                                <td> {{ number_format($categoryRevenue['revenue'], 2) }} </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>
    </div>
@endsection
