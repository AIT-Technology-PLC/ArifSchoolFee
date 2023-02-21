@extends('layouts.app')

@section('title', 'Expense Report')

@section('content')
    <x-common.fail-message :message="session('failedMessage')" />

    <x-common.report-filter
        action="{{ route('reports.expense') }}"
        export-route="reports.expense_export"
    >
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
                amount="{{ number_format($expenseReport->getTotalExpenseAfterTax, 2) }}"
                border-color="#fff"
                text-color="text-purple"
                label="Expense After Tax"
            ></x-common.index-insight>
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($expenseReport->getTotalExpenseBeforeTax, 2)"
                border-color="#fff"
                text-color="text-green"
                label="Expense Before Tax"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($expenseReport->getTotalExpenseVat, 2)"
                border-color="#fff"
                text-color="text-gold"
                label="Expense VAT"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($expenseReport->getTotalExpenseTot, 2)"
                border-color="#fff"
                text-color="text-blue"
                label="Expense TOT"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($expenseReport->getDailyAverageExpense, 2)"
                border-color="#fff"
                text-color="text-gold"
                label="Daily Average Expense"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($expenseReport->getAverageExpenseValue, 2)"
                border-color="#fff"
                text-color="text-purple"
                label="Average Transaction Value"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($expenseReport->getExpenseTransactionCount)"
                border-color="#fff"
                text-color="text-green"
                label="Number of Transactions"
            />
        </div>
        <div class="column is-6 p-lr-0">
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-green has-text-weight-medium is-size-6">
                        <span class="icon mr-1">
                            <i class="fas fa-user"></i>
                        </span>
                        <span>Top Suppliers by Expense</span>
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
                        <th><abbr> Supplier </abbr></th>
                        <th class="has-text-right"><abbr> Expense </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($expenseReport->getExpenseBySuppliers as $supplier)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $supplier->supplier_name ?? 'N/A' }} </td>
                                <td class="has-text-right"> {{ number_format($supplier->expense, 2) }} </td>
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
                        <span>Top Branches by Expense</span>
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
                        <th class="has-text-right"><abbr> Expense </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($expenseReport->getExpenseByBranches as $branch)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $branch->branch_name }} </td>
                                <td class="has-text-right"> {{ number_format($branch->expense, 2) }} </td>
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
                        <span>Top Categories by Expense</span>
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
                        <th class="has-text-right"><abbr> Expense </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($expenseReport->getExpenseByCategories as $category)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $category->category_name }} </td>
                                <td class="has-text-right"> {{ number_format($category->quantity, 2) }} </td>
                                <td class="has-text-right"> {{ number_format($category->expense, 2) }} </td>
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
                        <span>Top Items by Expense</span>
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
                        <th><abbr> Item </abbr></th>
                        <th class="has-text-right"><abbr> Quantity </abbr></th>
                        <th class="has-text-right"><abbr> Expense </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($expenseReport->getExpenseByItems as $item)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $item->name }} </td>
                                <td class="has-text-right"> {{ number_format($item->quantity, 2) }} </td>
                                <td class="has-text-right"> {{ number_format($item->expense, 2) }} </td>
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
                        <span>Expenses By Purchasers</span>
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
                        <th><abbr> Purchaser </abbr></th>
                        <th class="has-text-right"><abbr> Expense </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($expenseReport->getExpenseByPurchasers as $purchaser)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $purchaser->purchaser_name ?? 'Deleted Purchaser' }} </td>
                                <td class="has-text-right"> {{ number_format($purchaser->expense, 2) }} </td>
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
                            <i class="fas fa-money-bill-trend-up"></i>
                        </span>
                        <span>Expenses by Payment Methods</span>
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
                        <th><abbr> Payment Method </abbr></th>
                        <th class="has-text-right"><abbr> Transactions </abbr></th>
                        <th class="has-text-right"><abbr> Expense </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($expenseReport->getPaymentTypesByExpense as $paymentTypeExpense)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $paymentTypeExpense->payment_type }} </td>
                                <td class="has-text-right"> {{ $paymentTypeExpense->transactions }} </td>
                                <td class="has-text-right"> {{ number_format($paymentTypeExpense->expense, 2) }} </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>
    </div>
@endsection
