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
                    <div class="column is-6">
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
                                        >{{ $product->name }}</option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-th"
                                    class="is-large is-left"
                                />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label>
                            Brand
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.select
                                    id="brand_id"
                                    name="brand_id"
                                    class="is-size-7-mobile is-fullwidth"
                                    x-init="initializeSelect2($el)"
                                >
                                    <option
                                        value=" "
                                        @selected(request('brand_id') == '')
                                    > All </option>
                                    @foreach ($brands as $brand)
                                        <option
                                            value="{{ $brand->id }}"
                                            @selected(request('brand_id') == $brand->id)
                                        >{{ $brand->name }}</option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-trademark"
                                    class="is-large is-left"
                                />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label>
                            Category
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left is-expanded">
                                <x-forms.select
                                    id="category_id"
                                    name="category_id"
                                    class="is-size-7-mobile is-fullwidth"
                                    x-init="initializeSelect2($el)"
                                >
                                    <option
                                        value=" "
                                        @selected(request('category_id') == '')
                                    > All </option>
                                    @foreach ($categories as $category)
                                        <option
                                            value="{{ $category->id }}"
                                            @selected(request('category_id') == $category->id)
                                        >{{ $category->name }}</option>
                                    @endforeach
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-layer-group"
                                    class="is-large is-left"
                                />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </div>
        </div>
    </x-common.report-filter>

    <div class="columns is-marginless is-multiline mt-3">
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($profitReports->getCostOfGoodsSold, 2)"
                border-color="#fff"
                text-color="text-green"
                label="Cost of Goods Sold"
            ></x-common.index-insight>
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($profitReports->getBeginningInventoryCost->total_valuation, 2)"
                border-color="#fff"
                text-color="text-purple"
                label="Beginning Inventory Cost"
            ></x-common.index-insight>
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($profitReports->getNewCosts, 2)"
                border-color="#fff"
                text-color="text-gold"
                label="New Costs"
            ></x-common.index-insight>
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($profitReports->getEndingInventoryCost->total_valuation, 2)"
                border-color="#fff"
                text-color="text-blue"
                label="Ending Inventory Cost"
            ></x-common.index-insight>
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($saleReport->getTotalRevenueAfterTax, 2)"
                border-color="#fff"
                text-color="text-blue"
                label="Sales"
            ></x-common.index-insight>
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($saleReport->getTotalRevenueBeforeTax - $profitReports->getCostOfGoodsSold, 2)"
                border-color="#fff"
                text-color="text-gold"
                label="Profit"
            ></x-common.index-insight>
        </div>
        <div class="column is-6 p-lr-0">
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
                        <th class="has-text-right"><abbr> Cost </abbr></th>
                        <th class="has-text-right"><abbr> Sales </abbr></th>
                        <th class="has-text-right"><abbr> Profit </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($profitReports->getProfitByProducts as $getProfitByProduct)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $getProfitByProduct->product_name }} </td>
                                <td class="has-text-right"> {{ number_format($getProfitByProduct->total_cost, 2) }} </td>
                                <td class="has-text-right"> {{ number_format(collect($saleReport->getProductsByRevenue)->where('product_name', $getProfitByProduct->product_name)->first()?->revenue,2) }} </td>
                                <td class="has-text-right"> {{ number_format(collect($saleReport->getProductsByRevenue)->where('product_name', $getProfitByProduct->product_name)->first()?->revenue - $getProfitByProduct->total_cost,2) }} </td>
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
                        <th class="has-text-right"><abbr> Cost </abbr></th>
                        <th class="has-text-right"><abbr> Sales </abbr></th>
                        <th class="has-text-right"><abbr> Profit </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($profitReports->getProfitByBranchs as $getProfitByBranch)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $getProfitByBranch->branch_name }} </td>
                                <td class="has-text-right"> {{ number_format($getProfitByBranch->total_cost, 2) }} </td>
                                <td class="has-text-right"> {{ number_format(collect($saleReport->getBranchesByRevenue)->where('branch_name', $getProfitByBranch->branch_name)->first()?->revenue,2) }} </td>
                                <td class="has-text-right"> {{ number_format(collect($saleReport->getBranchesByRevenue)->where('branch_name', $getProfitByBranch->branch_name)->first()?->revenue - $getProfitByBranch->total_cost,2) }} </td>

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
                        <th class="has-text-right"><abbr> Cost </abbr></th>
                        <th class="has-text-right"><abbr> Sales </abbr></th>
                        <th class="has-text-right"><abbr> Profit </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($profitReports->getProfitByCategories as $getProfitByCategory)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $getProfitByCategory->category_name }} </td>
                                <td class="has-text-right"> {{ number_format($getProfitByCategory->total_cost, 2) }} </td>
                                <td class="has-text-right"> {{ number_format(collect($saleReport->getProductCategoriesByRevenue)->where('product_category_name', $getProfitByCategory->category_name)->first()?->revenue,2) }} </td>
                                <td class="has-text-right"> {{ number_format(collect($saleReport->getProductCategoriesByRevenue)->where('product_category_name', $getProfitByCategory->category_name)->first()?->revenue - $getProfitByCategory->total_cost,2) }} </td>
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
                        <th class="has-text-right"><abbr> Cost </abbr></th>
                        <th class="has-text-right"><abbr> Sales </abbr></th>
                        <th class="has-text-right"><abbr> Profit </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($profitReports->getProfitByBrands as $getProfitByBrand)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $getProfitByBrand->brand_name }} </td>
                                <td class="has-text-right"> {{ number_format($getProfitByBrand->total_cost, 2) }} </td>
                                <td class="has-text-right"> {{ number_format(collect($saleReport->getBrandsByRevenue)->where('brand_name', $getProfitByBrand->brand_name)->first()?->revenue,2) }} </td>
                                <td class="has-text-right"> {{ number_format(collect($saleReport->getBrandsByRevenue)->where('brand_name', $getProfitByBrand->brand_name)->first()?->revenue - $getProfitByBrand->total_cost,2) }} </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>
    </div>
@endsection
