@extends('layouts.app')

@section('title', 'Sales Return')

@section('content')
    <x-common.report-filter action="{{ route('reports.sales_return') }}">
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
        <div class="column is-6 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($transactionReport->getSalesTransactionCount(), 2)"
                border-color="#fff"
                text-color="text-green"
                label="Number Of Sales"
            />
        </div>
        <div class="column is-6 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($salesReturnReport->getReturnTransactionCount(), 2)"
                border-color="#fff"
                text-color="text-gold"
                label="Number Of Returns"
            />
        </div>
        <div class="column is-6 p-lr-0">
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-green has-text-weight-medium is-size-6">
                        <span class="icon mr-1">
                            <i class="fas fa-tags"></i>
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
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($salesReturnReport->getMostReturnedProducts() as $returnedProduct)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $returnedProduct['product'] }} </td>
                                <td class="has-text-right"> {{ quantity($returnedProduct['quantity']) }} </td>
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
                        <th class="has-text-right"><abbr> Return </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($salesReturnReport->getHighestReturningCustomers() as $customerReturn)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $customerReturn['customer'] }} </td>
                                <td class="has-text-right"> {{ quantity($customerReturn['quantity']) }} </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>
    </div>
@endsection
