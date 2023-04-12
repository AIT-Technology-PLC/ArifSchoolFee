@extends('layouts.app')

@section('title', 'Credit Report')

@section('content')
    <x-common.fail-message :message="session('failedMessage')" />

    <x-common.report-filter action="{{ route('reports.credit') }}">
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

    <div class="columns is-marginless is-multiline mt-3">
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                amount="{{ $creditReport->getTotalCreditGiven }}"
                border-color="#fff"
                text-color="text-green"
                label="Number Of Credits"
            ></x-common.index-insight>
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                amount="{{ $creditReport->getTotalCustomersReceivedCredit }}"
                border-color="#fff"
                text-color="text-purple"
                label="Number Of Customers"
            ></x-common.index-insight>
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                amount="{{ $creditReport->getTotalSettlementMade }}"
                border-color="#fff"
                text-color="text-gold"
                label="Number Of Settlements"
            ></x-common.index-insight>
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                amount="{{ $creditReport->getTotalCustomerMadeSettlement }}"
                border-color="#fff"
                text-color="text-blue"
                label="Number Of Customers"
            ></x-common.index-insight>
        </div>
        <div class="column is-6 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($creditReport->getTotalCreditAmount, 2)"
                border-color="#fff"
                text-color="text-blue"
                label="Total Credit Amount (In {{ userCompany()->currency }})"
            ></x-common.index-insight>
        </div>
        <div class="column is-6 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($creditReport->getTotalSettledAmount, 2)"
                border-color="#fff"
                text-color="text-gold"
                label="Total Settled Amount (In {{ userCompany()->currency }})"
            ></x-common.index-insight>
        </div>
        <div class="column is-6 p-lr-0">
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-green has-text-weight-medium is-size-6">
                        <span class="icon mr-1">
                            <i class="fas fa-user"></i>
                        </span>
                        <span>Credits Provided By Customers</span>
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
                        <th class="has-text-centered"><abbr> Transactions </abbr></th>
                        <th class="has-text-right"><abbr> Amount </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($creditReport->getTotalCreditByCustomer as $customer)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $customer->customer_name }} </td>
                                <td class="has-text-centered"> {{ $customer->transactions }} </td>
                                <td class="has-text-right"> {{ number_format($customer->credit_amount, 2) }} </td>
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
                        <span>Settlements Made By Customers</span>
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
                        <th class="has-text-centered"><abbr> Transactions </abbr></th>
                        <th class="has-text-right"><abbr> Amount </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($creditReport->getSettlmentByCustomer as $customer)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $customer->customer_name }} </td>
                                <td class="has-text-centered"> {{ $customer->transactions }} </td>
                                <td class="has-text-right"> {{ number_format($customer->credit_amount_settled, 2) }} </td>
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
                        <span>Credits Provided By Branches</span>
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
                        <th class="has-text-centered"><abbr> Transactions </abbr></th>
                        <th class="has-text-right"><abbr> Amount </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($creditReport->getTotalCreditByBranch as $branch)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $branch->warehouse_name }} </td>
                                <td class="has-text-centered"> {{ $branch->transactions }} </td>
                                <td class="has-text-right"> {{ number_format($branch->credit_amount, 2) }} </td>
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
                        <span>Credits Settled By Branches</span>
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
                        <th class="has-text-centered"><abbr> Transactions </abbr></th>
                        <th class="has-text-right"><abbr> Amount </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($creditReport->getSettlmentByBranch as $branch)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $branch->warehouse_name }} </td>
                                <td class="has-text-centered"> {{ $branch->transactions }} </td>
                                <td class="has-text-right"> {{ number_format($branch->credit_amount_settled, 2) }} </td>
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
                            <i class="fas fa-bank"></i>
                        </span>
                        <span>Credits Settled By Banks</span>
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
                        <th><abbr> Bank </abbr></th>
                        <th class="has-text-centered"><abbr> Transactions </abbr></th>
                        <th class="has-text-right"><abbr> Amount </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($creditReport->getSettlmentByBank as $bank)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $bank->bank_name ?? 'Cash Payment' }} </td>
                                <td class="has-text-centered"> {{ $bank->transactions }} </td>
                                <td class="has-text-right"> {{ number_format($bank->credit_amount_settled, 2) }} </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>
    </div>
@endsection
