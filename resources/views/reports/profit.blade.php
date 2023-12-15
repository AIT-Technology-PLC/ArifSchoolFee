@extends('layouts.app')

@section('title', 'Profit Report')

@section('content')
    <x-common.fail-message :message="session('failedMessage')" />

    <x-common.report-filter action="{{ route('reports.profit') }}">
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
                                    value="{{ request('period') ?? implode(' - ', [today()->subDays(30), today()]) }}"
                                />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-12">
                        <x-forms.label>
                            Product
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.select
                                    id="product_id"
                                    name="product_id"
                                    class="is-size-7-mobile is-fullwidth"
                                    x-init="initializeSelect2($el)"
                                >
                                    <option
                                        value=" "
                                        @selected(request('product_id') == '')
                                    > All </option>
                                    @foreach ($products as $product)
                                        <option
                                            value="{{ $product->id }}"
                                            @selected(request('product_id') == $product->id)
                                        >
                                            {{ $product->name }}
                                            @if (!empty($product->code))
                                                ({{ $product->code }})
                                            @endif
                                        </option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-large is-left"
                                />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </div>
        </div>
        <x-slot:buttons>
            <x-common.button
                tag="a"
                mode="button"
                label="Print"
                icon="fas fa-print"
                href="{{ route('reports.profit_print', request()->query()) }}"
                class="button btn-purple is-outlined has-text-weight-medium is-size-7-mobile"
            />
        </x-slot:buttons>
    </x-common.report-filter>

    <div class="columns is-marginless is-multiline mt-3">
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($profitReport->getCostOfGoodsSold, 2)"
                border-color="#fff"
                text-color="text-green"
                label="Cost of Goods Sold"
            ></x-common.index-insight>
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($profitReport->getBeginningInventoryCost, 2)"
                border-color="#fff"
                text-color="text-purple"
                label="Beginning Inventory Cost"
            ></x-common.index-insight>
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($profitReport->getNewCosts, 2)"
                border-color="#fff"
                text-color="text-gold"
                label="New Costs"
            ></x-common.index-insight>
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($profitReport->getEndingInventoryCost, 2)"
                border-color="#fff"
                text-color="text-blue"
                label="Ending Inventory Cost"
            ></x-common.index-insight>
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($profitReport->getTotalRevenueBeforeTax, 2)"
                border-color="#fff"
                text-color="text-blue"
                label="Revenue"
            ></x-common.index-insight>
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($profitReport->getProfit, 2)"
                border-color="#fff"
                text-color="text-green"
                label="Profit"
            ></x-common.index-insight>
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                amount="{{ number_format($profitReport->getProfitMarginInPercentage, 2) }}%"
                border-color="#fff"
                text-color="text-green"
                label="Profit Margin (%)"
            ></x-common.index-insight>
        </div>
        <div class="column is-12 p-lr-0">
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-green has-text-weight-medium is-size-6">
                        <span class="icon mr-1">
                            <i class="fas fa-th"></i>
                        </span>
                        <span>Profit by Products</span>
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
                        <th><abbr> Code </abbr></th>
                        <th><abbr> Unit </abbr></th>
                        <th class="has-text-right"><abbr> Cost of Goods Sold </abbr></th>
                        <th class="has-text-right"><abbr> Revenue </abbr></th>
                        <th class="has-text-right"><abbr> Profit </abbr></th>
                        <th class="has-text-right"><abbr> Margin </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($profitReport->getProfitByProducts as $getProfitByProduct)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $getProfitByProduct->product_name }} </td>
                                <td> {{ $getProfitByProduct->product_code ?? 'N/A' }} </td>
                                <td> {{ $getProfitByProduct->product_unit_of_measurement }} </td>
                                <td class="has-text-right"> {{ number_format($getProfitByProduct->total_cost, 2) }} </td>
                                <td class="has-text-right"> {{ number_format($getProfitByProduct->revenue, 2) }} </td>
                                <td class="has-text-right"> {{ number_format($getProfitByProduct->profit, 2) }} </td>
                                <td class="has-text-right">
                                    @if ($getProfitByProduct->revenue == 0)
                                        0.00%
                                    @else
                                        {{ number_format(($getProfitByProduct->profit / $getProfitByProduct->revenue) * 100, 2) }}%
                                    @endif
                                </td>
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
                            <i class="fas fa-warehouse"></i>
                        </span>
                        <span>Profit by Branches</span>
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
                        <th class="has-text-right"><abbr> Cost of Goods Sold </abbr></th>
                        <th class="has-text-right"><abbr> Revenue </abbr></th>
                        <th class="has-text-right"><abbr> Profit </abbr></th>
                        <th class="has-text-right"><abbr> Margin </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($profitReport->getProfitByBranches as $getProfitByBranch)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $getProfitByBranch->branch_name }} </td>
                                <td class="has-text-right"> {{ number_format($getProfitByBranch->total_cost, 2) }} </td>
                                <td class="has-text-right"> {{ number_format($getProfitByBranch->revenue, 2) }} </td>
                                <td class="has-text-right"> {{ number_format($getProfitByBranch->profit, 2) }} </td>
                                <td class="has-text-right">
                                    @if ($getProfitByBranch->revenue == 0)
                                        0.00%
                                    @else
                                        {{ number_format(($getProfitByBranch->profit / $getProfitByBranch->revenue) * 100, 2) }}%
                                    @endif
                                </td>
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
                        <span>Profit by Categories</span>
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
                        <th class="has-text-right"><abbr> Cost of Goods Sold </abbr></th>
                        <th class="has-text-right"><abbr> Revenue </abbr></th>
                        <th class="has-text-right"><abbr> Profit </abbr></th>
                        <th class="has-text-right"><abbr> Margin </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($profitReport->getProfitByCategories as $getProfitByCategory)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $getProfitByCategory->product_category_name }} </td>
                                <td class="has-text-right"> {{ number_format($getProfitByCategory->total_cost, 2) }} </td>
                                <td class="has-text-right"> {{ number_format($getProfitByCategory->revenue, 2) }} </td>
                                <td class="has-text-right"> {{ number_format($getProfitByCategory->profit, 2) }} </td>
                                <td class="has-text-right">
                                    @if ($getProfitByCategory->revenue == 0)
                                        0.00%
                                    @else
                                        {{ number_format(($getProfitByCategory->profit / $getProfitByCategory->revenue) * 100, 2) }}%
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>
        @if (isFeatureEnabled('Brand Management'))
            <div class="column is-6 p-lr-0">
                <x-content.header bg-color="has-background-white">
                    <x-slot:header>
                        <h1 class="title text-green has-text-weight-medium is-size-6">
                            <span class="icon mr-1">
                                <i class="fas fa-trademark"></i>
                            </span>
                            <span>Profit by Brands</span>
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
                            <th><abbr> Brand </abbr></th>
                            <th class="has-text-right"><abbr> Cost of Goods Sold </abbr></th>
                            <th class="has-text-right"><abbr> Revenue </abbr></th>
                            <th class="has-text-right"><abbr> Profit </abbr></th>
                            <th class="has-text-right"><abbr> Margin </abbr></th>
                        </x-slot>
                        <x-slot name="body">
                            @foreach ($profitReport->getProfitByBrands as $getProfitByBrand)
                                @continue(empty($getProfitByBrand->brand_name))
                                <tr>
                                    <td> {{ $loop->index + 1 }} </td>
                                    <td> {{ $getProfitByBrand->brand_name }} </td>
                                    <td class="has-text-right"> {{ number_format($getProfitByBrand->total_cost, 2) }} </td>
                                    <td class="has-text-right"> {{ number_format($getProfitByBrand->revenue, 2) }} </td>
                                    <td class="has-text-right"> {{ number_format($getProfitByBrand->profit, 2) }} </td>
                                    <td class="has-text-right">
                                        @if ($getProfitByBrand->revenue == 0)
                                            0.00%
                                        @else
                                            {{ number_format(($getProfitByBrand->profit / $getProfitByBrand->revenue) * 100, 2) }}%
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </x-slot>
                    </x-common.client-datatable>
                </x-content.footer>
            </div>
        @endif
    </div>
@endsection
