@extends('layouts.app')

@section('title', 'Customer Profile Report')

@section('content')
    <x-common.report-filter action="{{ route('reports.profile', $customer->id) }}">
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
                :amount="$revenueReport->getTotalTransactions ?? 0.0"
                border-color="#fff"
                text-color="text-green"
                label="Total Transactions"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                amount="{{ money($revenueReport->getLifetimeValue * 1.15) ?? 0.0 }}"
                border-color="#fff"
                text-color="text-gold"
                label="Lifetime Value"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                amount="{{ money($revenueReport->getAverageTransactionValue) ?? 0.0 }}"
                border-color="#fff"
                text-color="text-purple"
                label="Average Transaction Value"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="0.0"
                border-color="#fff"
                text-color="text-purple"
                label="Purchase Frequency"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                amount="{{ money($customerProfileReport->getCreditAmout->credit_amount ?? 0.0) }}"
                border-color="#fff"
                text-color="text-green"
                label="Credit Amount"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                amount="{{ money($customerProfileReport->getCreditAmout->unsettled_amount ?? 0.0) }}"
                border-color="#fff"
                text-color="text-gold"
                label="Unsettled Credit Amount"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                amount="{{ number_format($customerProfileReport->getAverageCreditSettlementInDays, 2) ?? 0.0 }} Days"
                border-color="#fff"
                text-color="text-purple"
                label="Average Credit Settlement in Days"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                amount="{{ date('d-m-Y', strtotime($revenueReport->getLastPurchaseDateAndValue?->issued_on)) }}  | {{ money($revenueReport->getLastPurchaseDateAndValue?->value) }}"
                border-color="#fff"
                text-color="text-green"
                label="Last Purchase Date & Value"
            />
        </div>
        <div class="column is-6 p-lr-0">
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-green has-text-weight-medium is-size-6">
                        <span class="icon mr-1">
                            <i class="fas fa-warehouse"></i>
                        </span>
                        <span>Revenue by Branch</span>
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
                        <th><abbr>Branch </abbr></th>
                        <th class="has-text-right"><abbr>Revenue </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($revenueReport->getRevenueByBranch() as $revenueByBranch)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $revenueByBranch->warehouse_name }} </td>
                                <td class="has-text-right"> {{ number_format($revenueByBranch->revenue, 2) }} </td>
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
                        <span>Revenue by Sales Rep</span>
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
                        <th><abbr>Sales Rep </abbr></th>
                        <th class="has-text-right"><abbr>Revenue </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($revenueReport->getRevenueBySalesRep() as $revenueBySalesRep)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $revenueBySalesRep->user_name }} </td>
                                <td class="has-text-right"> {{ number_format($revenueBySalesRep->revenue, 2) }} </td>
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
                        <span>Favorite Categories</span>
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
                        @foreach ($revenueReport->getFavoriteCategory as $favoriteCategory)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $favoriteCategory->product_category_name }} </td>
                                <td class="has-text-right"> {{ number_format($favoriteCategory->quantity, 2) }} </td>
                                <td class="has-text-right"> {{ number_format($favoriteCategory->revenue, 2) }} </td>
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
                        <span>Favorite Products</span>
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
                        @foreach ($revenueReport->getFavoriteProducts as $favoriteProduct)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $favoriteProduct->product_name }} </td>
                                <td class="has-text-right"> {{ quantity($favoriteProduct->quantity, $favoriteProduct->product_unit_of_measurement) }} </td>
                                <td class="has-text-right"> {{ number_format($favoriteProduct->revenue, 2) }} </td>
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
                        <span>Favorite Payment Method</span>
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
                        <th><abbr> Payment type </abbr></th>
                        <th class="has-text-right"><abbr> Transaction </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($revenueReport->getFavoritePaymentMethod as $favoritePaymentMethod)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $favoritePaymentMethod->payment_type }} </td>
                                <td class="has-text-right"> {{ $favoritePaymentMethod->payment_count }} </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>
    </div>
@endsection
