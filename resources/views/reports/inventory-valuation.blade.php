@extends('layouts.app')

@section('title', 'Inventory Valuation Report')

@section('content')
    <x-common.fail-message :message="session('failedMessage')" />

    <x-common.report-filter action="{{ route('reports.inventory_valuation') }}">
        <div class="quickview-body">
            <div class="quickview-block">
                <div class="columns is-marginless is-vcentered is-multiline is-mobile">
                    <div class="column is-12">
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

    <div class="columns is-marginless is-multiline mt-3">
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="money($inventoryValuationReports->getTotalInventoryValue->total_cost)"
                border-color="#fff"
                text-color="text-green"
                label="Total Inventory value"
            ></x-common.index-insight>
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($inventoryValuationReports->getTotalProductsInInventory)"
                border-color="#fff"
                text-color="text-gold"
                label="Total Products in inventory"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($totalNumberOfBranches)"
                border-color="#fff"
                text-color="text-blue"
                label="Total Branches"
            />
        </div>
        <div class="column is-6 p-lr-0">
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-green has-text-weight-medium is-size-6">
                        <span class="icon mr-1">
                            <i class="fas fa-th"></i>
                        </span>
                        <span>Valuation by Products</span>
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
                        <th class="has-text-right"><abbr> Quantity </abbr></th>
                        <th class="has-text-right"><abbr> Unit Cost </abbr></th>
                        <th class="has-text-right"><abbr> Total Cost </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($inventoryValuationReports->getValuationByProducts as $getValuationByProduct)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $getValuationByProduct->product_name }} </td>
                                <td> {{ $getValuationByProduct->product_code }} </td>
                                <td class="has-text-right"> {{ $getValuationByProduct->quantity }} {{ $getValuationByProduct->unit_of_measurement }} </td>
                                <td class="has-text-right"> {{ number_format($getValuationByProduct->unit_cost, 2) }} </td>
                                <td class="has-text-right"> {{ number_format($getValuationByProduct->unit_cost * $getValuationByProduct->quantity, 2) }} </td>
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
                        <span>Valuation by Branches</span>
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
                        <th class="has-text-right"><abbr> Total Cost </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($inventoryValuationReports->getValuationByBranch as $getValuationByBranch)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $getValuationByBranch->warehouse_name }} </td>
                                <td class="has-text-right"> {{ number_format($getValuationByBranch->total_cost, 2) }} </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>
    </div>
@endsection
