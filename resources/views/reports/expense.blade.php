@extends('layouts.app')

@section('title', 'Expense')

@section('content')
    <x-common.report-filter action="{{ route('reports.expense') }}">
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
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                amount="{{ number_format($expenseReport->getTotalExpenseAfterTax(), 2) }}"
                border-color="#fff"
                text-color="text-purple"
                label="Expense After VAT"
            ></x-common.index-insight>
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($expenseReport->getTotalExpenseBeforeTax(), 2)"
                border-color="#fff"
                text-color="text-green"
                label="Expense Before VAT"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($expenseReport->getTotalExpenseTax(), 2)"
                border-color="#fff"
                text-color="text-gold"
                label="Expense Tax"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($expenseReport->getDailyAverageExpense(), 2)"
                border-color="#fff"
                text-color="text-blue"
                label="Daily Average Expense"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($expenseTransactionReport->getAverageTransactionValue(), 2)"
                border-color="#fff"
                text-color="text-gold"
                label="Average Purchase Value"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($expenseTransactionReport->transactionCount)"
                border-color="#fff"
                text-color="text-purple"
                label="Number Of Purchases"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($expenseTransactionReport->getAverageExpensePerTransaction())"
                border-color="#fff"
                text-color="text-green"
                label="Average Expense Per transaction"
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
                        @foreach ($expenseReport->getSuppliersByExpense() as $supplierExpense)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $supplierExpense['supplier'] ?? 'N/A' }} </td>
                                <td class="has-text-right"> {{ number_format($supplierExpense['expense'], 2) }} </td>
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
                        @foreach ($expenseReport->getBranchesByExpense() as $branchExpense)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $branchExpense['branch'] }} </td>
                                <td class="has-text-right"> {{ number_format($branchExpense['expense'], 2) }} </td>
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
                        <span>Purchaser board</span>
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
                        @foreach ($expenseReport->getPurchaserByExpense() as $purchaseExpense)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $purchaseExpense['purchaser'] ?? 'Deleted Salesperson' }} </td>
                                <td class="has-text-right"> {{ number_format($purchaseExpense['expense'], 2) }} </td>
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
                        @foreach ($expenseReport->getExpenseCategoriesByExpense() as $categoryExpense)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $categoryExpense['category'] }} </td>
                                <td class="has-text-right"> {{ number_format($categoryExpense['quantity'], 2) }} </td>
                                <td class="has-text-right"> {{ number_format($categoryExpense['expense'], 2) }} </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>
    </div>
@endsection
