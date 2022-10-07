@extends('layouts.app')

@section('title', 'Sales Return Report')

@section('content')
    <x-common.report-filter action="{{ route('reports.return') }}">
        <div class="quickview-body">
            <div class="quickview-block">
                <div class="columns is-marginless is-vcentered is-multiline">
                    <div class="column is-6 p-lr-20 pt-4">
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
                    <div class="column is-6 p-lr-20 pt-4">
                        <x-forms.label>
                            Period
                        </x-forms.label>
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
            </div>
        </div>
    </x-common.report-filter>

    <div class="columns is-marginless is-multiline mt-3">
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                amount="{{ number_format($returnReport->getTotalRevenueAfterTax, 2) }}"
                border-color="#fff"
                text-color="text-purple"
                label="Revenue After VAT"
            ></x-common.index-insight>
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($returnReport->getTotalRevenueBeforeTax, 2)"
                border-color="#fff"
                text-color="text-green"
                label="Revenue Before VAT"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($returnReport->getTotalRevenueTax, 2)"
                border-color="#fff"
                text-color="text-gold"
                label="Revenue VAT"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="$returnReport->getCustomersCount"
                border-color="#fff"
                text-color="text-blue"
                label="Customers"
            />
        </div>
        <div class="column is-12 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="$returnReport->getReturnsCount"
                border-color="#fff"
                text-color="text-purple"
                label="Number Of Returns"
            />
        </div>
        <div class="column is-6 p-lr-0">
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-green has-text-weight-medium is-size-6">
                        <span class="icon mr-1">
                            <i class="fas fa-th"></i>
                        </span>
                        <span>Most Returned Products</span>
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
                        @foreach ($returnReport->getReturnsByProducts as $product)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $product->product_name }} </td>
                                <td class="has-text-right"> {{ quantity($product->quantity) }} </td>
                                <td class="has-text-right"> {{ number_format($product->revenue * 1.15, 2) }} </td>
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
                        <span>Highest Returning Customers</span>
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
                        <th class="has-text-right"><abbr> Returns </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($returnReport->getReturnsByCustomers as $customer)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $customer->customer_name ?? 'N/A' }} </td>
                                <td class="has-text-right"> {{ number_format($customer->revenue * 1.15, 2) }} </td>
                                <td class="has-text-right"> {{ $customer->returns }} </td>
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
                        <span>Top Returning Branches</span>
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
                        <th class="has-text-right"><abbr> Returns </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($returnReport->getReturnsByBranches as $branch)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $branch->branch_name }} </td>
                                <td class="has-text-right"> {{ number_format($branch->revenue, 2) }} </td>
                                <td class="has-text-right"> {{ number_format($branch->returns, 2) }} </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>
    </div>
@endsection
