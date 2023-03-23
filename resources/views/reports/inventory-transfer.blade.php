@extends('layouts.app')

@section('title', 'Inventory Transfer Report')

@section('content')
    <x-common.fail-message :message="session('failedMessage')" />

    <x-common.report-filter action="{{ route('reports.inventory_transfer') }}">
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
                            From
                        </x-forms.label>
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id="from"
                                    name="from"
                                    class="is-size-7-mobile is-fullwidth"
                                >
                                    <option disabled> Branches </option>
                                    <option
                                        value=""
                                        @selected(request('from') == '')
                                    > All </option>
                                    @foreach ($warehouses as $warehouse)
                                        <option
                                            value="{{ $warehouse->id }}"
                                            @selected(request('from') == $warehouse->id)
                                        > {{ $warehouse->name }} </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label>
                            To
                        </x-forms.label>
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id="to"
                                    name="to"
                                    class="is-size-7-mobile is-fullwidth"
                                >
                                    <option disabled> Branches </option>
                                    <option
                                        value=""
                                        @selected(request('to') == '')
                                    > All </option>
                                    @foreach ($warehouses as $warehouse)
                                        <option
                                            value="{{ $warehouse->id }}"
                                            @selected(request('to') == $warehouse->id)
                                        > {{ $warehouse->name }} </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label>
                            Prepared By
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
                    <div class="column is-6">
                        <x-forms.label>
                            Products
                        </x-forms.label>
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id="product_id"
                                    name="product_id"
                                    class="is-size-7-mobile is-fullwidth"
                                >
                                    <option
                                        value=""
                                        @selected(request('product_id') == '')
                                    > All </option>
                                    @foreach ($products as $product)
                                        <option
                                            value="{{ $product->id }}"
                                            @selected(request('product_id') == $product->id)
                                        >{{ $product->name }}</option>
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
        <div class="column is-12 p-lr-0">
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-green has-text-weight-medium is-size-6">
                        <span class="icon mr-1">
                            <i class="fas fa-chart-bar"></i>
                        </span>
                        <span>
                            Inventory Transfer Report
                        </span>
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
                        <th><abbr> Date </abbr></th>
                        <th><abbr> Product </abbr></th>
                        <th><abbr> Quantity </abbr></th>
                        <th><abbr> Origin </abbr></th>
                        <th><abbr> Destination </abbr></th>
                        <th><abbr> Employee </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($inventoryTransferReport->getInventoryTransfers as $report)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $report->transfer->issued_on->toFormattedDateString() ?? 'N/A' }} </td>
                                <td> {{ $report->product->name ?? 'N/A' }} </td>
                                <td> {{ $report->quantity ?? 'N/A' }} {{ $report->product->unit_of_measurement ?? 'N/A' }}</td>
                                <td> {{ $report->transfer->transferredFrom->name ?? 'N/A' }} </td>
                                <td> {{ $report->transfer->transferredTo->name ?? 'N/A' }} </td>
                                <td> {{ $report->transfer->createdBy->name ?? 'N/A' }} </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>
    </div>
@endsection
