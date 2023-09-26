@extends('layouts.app')

@section('title', 'Sale By Payment Report')

@section('content')
    <x-common.fail-message :message="session('failedMessage')" />

    <x-common.report-filter
        action="{{ route('reports.sale_by_payment') }}"
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
                    <div class="column is-6">
                        <x-forms.label>
                            Payment Method
                        </x-forms.label>
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id="payment_method"
                                    name="payment_method"
                                    class="is-size-7-mobile is-fullwidth"
                                >
                                    <option disabled> Payment Method </option>
                                    <option
                                        value=""
                                        @selected(request('payment_method') == '')
                                    > All </option>
                                    @foreach (['Cash Payment', 'Credit Payment', 'Bank Deposit', 'Bank Transfer', 'Deposits', 'Cheque'] as $payment_method)
                                        <option
                                            value="{{ $payment_method }}"
                                            @selected(request('payment_method') == $payment_method)
                                        > {{ $payment_method }} </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6">
                        <x-forms.label>
                            Customer
                        </x-forms.label>
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id="customer_id"
                                    name="customer_id"
                                    class="is-size-7-mobile is-fullwidth"
                                >
                                    <option disabled> Customers </option>
                                    <option
                                        value=""
                                        @selected(request('customer_id') == '')
                                    > All </option>
                                    @foreach ($customers as $customer)
                                        <option
                                            value="{{ $customer->id }}"
                                            @selected(request('customer_id') == $customer->id)
                                        >{{ $customer->company_name }}</option>
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
                            <i class="fas fa-credit-card"></i>
                        </span>
                        <span>Sales By Payment</span>
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
                        <th><abbr> Transaction No </abbr></th>
                        <th><abbr> Customer </abbr></th>
                        <th><abbr> Amount </abbr></th>
                        @if (request('payment_method') == '' || request('payment_method') == 'Credit Payment')
                            <th class="has-text-right"><abbr> Credit Amount </abbr></th>
                            <th class="has-text-right"><abbr> Settled Amount </abbr></th>
                            <th class="has-text-right"><abbr> Remaining Amount </abbr></th>
                            <th><abbr> Last Settled Date </abbr></th>
                        @endif
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($saleReport->getSalesByPaymentMethods as $salesByPaymentMethod)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td data-sort="{{ $salesByPaymentMethod->issued_on }}"> {{ carbon($salesByPaymentMethod->issued_on)->toDayDateTimeString() }} </td>
                                <td> {{ $salesByPaymentMethod->code }} </td>
                                <td> {{ $salesByPaymentMethod->customer_name ?? 'N/A' }} </td>
                                <td
                                    class="has-text-right"
                                    data-sort="{{ $salesByPaymentMethod->amount }}"
                                > {{ number_format($salesByPaymentMethod->amount, 2) }} </td>
                                @if (request('payment_method') == '' || request('payment_method') == 'Credit Payment')
                                    <td
                                        class="has-text-right"
                                        data-sort="{{ $salesByPaymentMethod->credit_amount }}"
                                    > {{ number_format($salesByPaymentMethod->credit_amount, 2) }} </td>
                                    <td
                                        class="has-text-right"
                                        data-sort="{{ $salesByPaymentMethod->credit_amount_settled }}"
                                    > {{ number_format($salesByPaymentMethod->credit_amount_settled, 2) }} </td>
                                    <td
                                        class="has-text-right"
                                        data-sort="{{ $salesByPaymentMethod->credit_amount_unsettled }}"
                                    > {{ number_format($salesByPaymentMethod->credit_amount_unsettled, 2) }} </td>
                                    <td data-sort="{{ $salesByPaymentMethod?->last_settled_at }}"> {{ carbon($salesByPaymentMethod->last_settled_at)?->toDayDateTimeString() }} </td>
                                @endif
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>
    </div>
@endsection
