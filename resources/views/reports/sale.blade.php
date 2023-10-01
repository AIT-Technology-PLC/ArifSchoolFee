@extends('layouts.app')

@section('title', 'Sales Report')

@section('content')
    <x-common.fail-message :message="session('failedMessage')" />

    <x-common.report-filter
        action="{{ route('reports.sale') }}"
        export-route="reports.sale_export"
    >
        <div class="quickview-body">
            <div class="quickview-block">
                <div class="columns is-marginless is-vcentered is-multiline is-mobile">
                    @if (isFeatureEnabled('Sale Management') && isFeatureEnabled('Gdn Management'))
                        <div class="column is-12">
                            <x-forms.label>
                                Source
                            </x-forms.label>
                            <x-forms.field class="has-text-centered">
                                <x-forms.control>
                                    <x-forms.select
                                        id="source"
                                        name="source"
                                        class="is-size-7-mobile is-fullwidth"
                                    >
                                        <option
                                            value="Delivery Orders"
                                            @selected((request('source') ?? userCompany()->sales_report_source) == 'Delivery Orders')
                                        > Delivery Orders </option>
                                        <option
                                            value="Invoices"
                                            @selected((request('source') ?? userCompany()->sales_report_source) == 'Invoices')
                                        > Invoices </option>
                                    </x-forms.select>
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                    @endif
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
                            Salesperson
                        </x-forms.label>
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id="user_id"
                                    name="user_id"
                                    class="is-size-7-mobile is-fullwidth"
                                >
                                    <option disabled> Employees </option>
                                    <option
                                        value=""
                                        @selected(request('user_id') == '')
                                    > All </option>
                                    @foreach ($users as $user)
                                        <option
                                            value="{{ $user->id }}"
                                            @selected(request('user_id') == $user->id)
                                        >{{ $user->name }}</option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </div>
        </div>
    </x-common.report-filter>

    <div class="columns is-marginless is-multiline mt-3">
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                amount="{{ number_format($saleReport->getTotalRevenueAfterTax, 2) }}"
                border-color="#fff"
                text-color="text-purple"
                label="Revenue After Tax"
            ></x-common.index-insight>
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($saleReport->getTotalRevenueBeforeTax, 2)"
                border-color="#fff"
                text-color="text-green"
                label="Revenue Before Tax"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($saleReport->getTotalRevenueTax, 2)"
                border-color="#fff"
                text-color="text-gold"
                label="Revenue Tax"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($saleReport->getDailyAverageRevenue, 2)"
                border-color="#fff"
                text-color="text-blue"
                label="Daily Average Revenue"
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
                :amount="number_format($saleReport->getSalesCount)"
                border-color="#fff"
                text-color="text-purple"
                label="Number Of Sales"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                amount="{{ number_format($saleReport->getCashSalesPercentage) }}%"
                border-color="#fff"
                text-color="text-blue"
                label="Cash Sales Percentage"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($saleReport->getAverageItemsPerSale)"
                border-color="#fff"
                text-color="text-green"
                label="Basket Size Analysis"
            />
        </div>
        <div class="column is-12 p-lr-0">
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-green has-text-weight-medium is-size-6">
                        <span class="icon mr-1">
                            <i class="fas fa-magnifying-glass-chart"></i>
                        </span>
                        <span>Sales Summary</span>
                    </h1>
                </x-slot:header>
            </x-content.header>
            <x-content.footer>
                <x-common.client-datatable
                    has-filter="false"
                    has-length-change="false"
                    paging-type="simple"
                    length-menu="[5]"
                    x-init="hideColumns($el.id, [9])"
                >
                    <x-slot name="headings">
                        <th><abbr> # </abbr></th>
                        <th><abbr> Date </abbr></th>
                        <th><abbr> Saleperson </abbr></th>
                        <th><abbr> Branch </abbr></th>
                        <th><abbr> Customer </abbr></th>
                        <th><abbr> Payment Method </abbr></th>
                        <th><abbr> Product </abbr></th>
                        <th><abbr> Category </abbr></th>
                        <th><abbr> Unit </abbr></th>
                        <th><abbr> From </abbr></th>
                        <th class="has-text-right"><abbr> Quantity </abbr></th>
                        <th class="has-text-right"><abbr> Unit Price </abbr></th>
                        <th class="has-text-right"><abbr> Total Price </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($saleReport->getSalesDetails as $salesDetail)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td data-sort="{{ $salesDetail->issued_on }}"> {{ carbon($salesDetail->issued_on)->toDayDateTimeString() }} </td>
                                <td> {{ $salesDetail->user_name }} </td>
                                <td> {{ $salesDetail->branch_name }} </td>
                                <td> {{ $salesDetail->customer_name ?? 'N/A' }} </td>
                                <td> {{ $salesDetail->payment_type }} </td>
                                <td> {{ $salesDetail->product_name }} </td>
                                <td> {{ $salesDetail->product_category_name }} </td>
                                <td> {{ $salesDetail->product_unit_of_measurement }} </td>
                                <td> {{ $salesDetail->warehouse_name ?? 'N/A' }} </td>
                                <td
                                    class="has-text-right"
                                    data-sort="{{ $salesDetail->quantity }}"
                                > {{ number_format($salesDetail->quantity, 2) }} </td>
                                <td
                                    class="has-text-right"
                                    data-sort="{{ $salesDetail->unit_price }}"
                                > {{ number_format($salesDetail->unit_price, 2) }} </td>
                                <td
                                    class="has-text-right"
                                    data-sort="{{ $salesDetail->line_price_before_tax }}"
                                > {{ number_format($salesDetail->line_price_before_tax, 2) }} </td>
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
                    length-menu="[5]"
                    x-init="hideColumns($el.id, {{ !is_null(request('product_id')) ? '[2, 3]' : '[]' }})"
                >
                    <x-slot name="headings">
                        <th><abbr> # </abbr></th>
                        <th><abbr> Customer </abbr></th>

                        @if (!is_null(request('product_id')))
                            <th><abbr> Quantity </abbr></th>
                            <th><abbr> Unit Price </abbr></th>
                        @endif

                        <th class="has-text-right"><abbr> Revenue </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($saleReport->getCustomersByRevenue as $customerRevenue)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $customerRevenue->customer_name ?? 'N/A' }} </td>

                                @if (!is_null(request('product_id')))
                                    <td> {{ $customerRevenue->quantity }} {{ $customerRevenue->product_unit_of_measurement }}</td>
                                    <td>
                                        @foreach ($saleReport->getUnitPricesWhere('customer_id', $customerRevenue->customer_id) as $unitPrice)
                                            - {{ number_format($unitPrice, 2) }}
                                            <br>
                                        @endforeach
                                    </td>
                                @endif

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
                    length-menu="[5]"
                    x-init="hideColumns($el.id, {{ !is_null(request('product_id')) ? '[2, 3]' : '[]' }})"
                >
                    <x-slot name="headings">
                        <th><abbr> # </abbr></th>
                        <th><abbr> Branch </abbr></th>

                        @if (!is_null(request('product_id')))
                            <th><abbr> Quantity </abbr></th>
                            <th><abbr> Unit Price </abbr></th>
                        @endif

                        <th class="has-text-right"><abbr> Revenue </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($saleReport->getBranchesByRevenue as $branchRevenue)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $branchRevenue->branch_name }} </td>

                                @if (!is_null(request('product_id')))
                                    <td> {{ $branchRevenue->quantity }} {{ $branchRevenue->product_unit_of_measurement }}</td>
                                    <td>
                                        @foreach ($saleReport->getUnitPricesWhere('branch_id', $branchRevenue->branch_id) as $unitPrice)
                                            - {{ number_format($unitPrice, 2) }}
                                            <br>
                                        @endforeach
                                    </td>
                                @endif

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
                    length-menu="[5]"
                    x-init="hideColumns($el.id, {{ !is_null(request('product_id')) ? '[2, 3]' : '[]' }})"
                >
                    <x-slot name="headings">
                        <th><abbr> # </abbr></th>
                        <th><abbr> Salesperson </abbr></th>

                        @if (!is_null(request('product_id')))
                            <th><abbr> Quantity </abbr></th>
                            <th><abbr> Unit Price </abbr></th>
                        @endif

                        <th class="has-text-right"><abbr> Revenue </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($saleReport->getRepsByRevenue as $salesRevenue)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $salesRevenue->user_name ?? 'Deleted Salesperson' }} </td>

                                @if (!is_null(request('product_id')))
                                    <td> {{ $salesRevenue->quantity }} {{ $salesRevenue->product_unit_of_measurement }}</td>
                                    <td>
                                        @foreach ($saleReport->getUnitPricesWhere('created_by', $salesRevenue->created_by) as $unitPrice)
                                            - {{ number_format($unitPrice, 2) }}
                                            <br>
                                        @endforeach
                                    </td>
                                @endif

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
                    length-menu="[5]"
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
                    length-menu="[5]"
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
                    length-menu="[5]"
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
        @if (isFeatureEnabled('Brand Management'))
            <div class="column is-6 p-lr-0">
                <x-content.header bg-color="has-background-white">
                    <x-slot:header>
                        <h1 class="title text-green has-text-weight-medium is-size-6">
                            <span class="icon mr-1">
                                <i class="fas fa-trademark"></i>
                            </span>
                            <span>Best-Selling Brands</span>
                        </h1>
                    </x-slot:header>
                </x-content.header>
                <x-content.footer>
                    <x-common.client-datatable
                        has-filter="false"
                        has-length-change="false"
                        paging-type="simple"
                        length-menu="[5]"
                    >
                        <x-slot name="headings">
                            <th><abbr> # </abbr></th>
                            <th><abbr> Brand </abbr></th>
                            <th class="has-text-right"><abbr> Revenue </abbr></th>
                        </x-slot>
                        <x-slot name="body">
                            @foreach ($saleReport->getBrandsByRevenue as $brandRevenue)
                                <tr>
                                    <td> {{ $loop->index + 1 }} </td>
                                    <td> {{ $brandRevenue->brand_name ?? 'N/A' }} </td>
                                    <td class="has-text-right"> {{ number_format($brandRevenue->revenue, 2) }} </td>
                                </tr>
                            @endforeach
                        </x-slot>
                    </x-common.client-datatable>
                </x-content.footer>
            </div>
        @endif
    </div>
@endsection
