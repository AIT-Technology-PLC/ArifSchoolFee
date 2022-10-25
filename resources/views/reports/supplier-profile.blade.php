@extends('layouts.app')

@section('title', str($supplier->company_name)->title() . ' Profile')

@section('content')
    <h1 class="mx-3 m-lr-0 mb-4 text-green has-text-weight-bold is-size-5 is-size-6-mobile is-uppercase has-text-centered">
        <span class="icon">
            <i class="fas fa-user"></i>
        </span>
        <span>
            {{ $supplier->company_name }} Profile
        </span>
    </h1>

    <x-common.report-filter action="{{ route('reports.supplier_profile', $supplier->id) }}">
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
                </div>
            </div>
        </div>
    </x-common.report-filter>

    <h1 class="mx-3 m-lr-0 mt-5 text-green has-text-weight-medium is-size-6-mobile">
        <span class="icon">
            <i class="fas fa-calendar"></i>
        </span>
        <span>
            Lifetime Purchases Summary
        </span>
    </h1>

    <div class="columns is-marginless is-multiline">
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($lifeTimeSupplierReport->getTotalPurchaseExpenseAfterTax, 2)"
                border-color="#fff"
                text-color="text-green"
                label="Purchase Expense (After VAT)"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="$lifeTimeSupplierReport->getPurchaseCount"
                border-color="#fff"
                text-color="text-purple"
                label="Number of Purchases"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($lifeTimeSupplierReport->getAverageItemsPerPurchase)"
                border-color="#fff"
                text-color="text-gold"
                label="Basket Size Analysis"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($totalDebtAmountProvided, 2)"
                border-color="#fff"
                text-color="text-blue"
                label="Debt Amount"
            />
        </div>
    </div>

    <h1 class="mx-3 m-lr-0 mt-5 text-green has-text-weight-medium is-size-6-mobile">
        <span class="icon">
            <i class="fas fa-calendar"></i>
        </span>
        <span>
            Lifetime Expenses Summary
        </span>
    </h1>

    <div class="columns is-marginless is-multiline">
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                amount="{{ number_format($lifetimeExpenseReport->getTotalExpenseAfterTax, 2) }}"
                border-color="#fff"
                text-color="text-purple"
                label="Expense After VAT/TOT"
            ></x-common.index-insight>
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($lifetimeExpenseReport->getTotalExpenseBeforeTax, 2)"
                border-color="#fff"
                text-color="text-green"
                label="Expense Before VAT/TOT"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($lifetimeExpenseReport->getTotalExpenseVat, 2)"
                border-color="#fff"
                text-color="text-gold"
                label="Expense VAT"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($lifetimeExpenseReport->getTotalExpenseTot, 2)"
                border-color="#fff"
                text-color="text-blue"
                label="Expense TOT"
            />
        </div>
        <div class="column is-6 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($lifetimeExpenseReport->getAverageExpenseValue, 2)"
                border-color="#fff"
                text-color="text-purple"
                label="Average Transaction Value"
            />
        </div>
        <div class="column is-6 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($lifetimeExpenseReport->getExpenseTransactionCount)"
                border-color="#fff"
                text-color="text-green"
                label="Number of Transactions"
            />
        </div>
    </div>

    <h1 class="mx-3 m-lr-0 mt-5 text-green has-text-weight-medium is-size-6-mobile">
        <span class="icon">
            <i class="fas fa-money-check-dollar"></i>
        </span>
        <span>
            Debt Summary
        </span>
    </h1>

    <div class="columns is-marginless is-multiline">
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($supplier->debt_amount_limit, 2)"
                border-color="#fff"
                text-color="text-green"
                label="Debt Limit"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($currentDebtLimit, 2)"
                border-color="#fff"
                text-color="text-purple"
                label="Current Debt Limit"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($currentDebtBalance, 2)"
                border-color="#fff"
                text-color="text-gold"
                label="Unsettled Debt Amount"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                amount="{{ number_format($averageDebtSettlementDays, 2) }} Days"
                border-color="#fff"
                text-color="text-blue"
                label="Debt Settlement Duration"
            />
        </div>
    </div>

    <h1 class="mx-3 m-lr-0 mt-5 text-green has-text-weight-medium is-size-6-mobile">
        <span class="icon">
            <i class="fas fa-filter"></i>
        </span>
        <span>
            Filtered Purchases Report
        </span>
    </h1>

    <div class="columns is-marginless is-multiline">
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($supplierReport->getTotalPurchaseExpenseAfterTax, 2)"
                border-color="#fff"
                text-color="text-green"
                label="Purchase Expense (After VAT)"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="$supplierReport->getPurchaseCount"
                border-color="#fff"
                text-color="text-purple"
                label="Number of Purchases"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($supplierReport->getAveragePurchaseExpenseValue, 2)"
                border-color="#fff"
                text-color="text-gold"
                label="Average Purchase Expense Value"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($supplierReport->getAverageItemsPerPurchase)"
                border-color="#fff"
                text-color="text-blue"
                label="Basket Size Analysis"
            />
        </div>
    </div>

    <div class="columns is-marginless is-multiline">
        <div class="column is-6 p-lr-0">
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-green has-text-weight-medium is-size-6">
                        <span class="icon mr-1">
                            <i class="fas fa-th"></i>
                        </span>
                        <span>Best-Purchasing Products</span>
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
                        <th class="has-text-right"><abbr> Expense </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($supplierReport->getProductsByPurchaseExpense as $productPurchaseExpense)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $productPurchaseExpense->product_name }} </td>
                                <td class="has-text-right"> {{ quantity($productPurchaseExpense->quantity, $productPurchaseExpense->product_unit_of_measurement) }} </td>
                                <td class="has-text-right"> {{ number_format($productPurchaseExpense->expense, 2) }} </td>
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
                    length-menu=[5]
                >
                    <x-slot name="headings">
                        <th><abbr> # </abbr></th>
                        <th><abbr> Category </abbr></th>
                        <th class="has-text-right"><abbr> Quantity </abbr></th>
                        <th class="has-text-right"><abbr> Expense </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($supplierReport->getProductCategoriesByPurchaseExpense as $categoryPurchaseExpense)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $categoryPurchaseExpense->product_category_name }} </td>
                                <td class="has-text-right"> {{ number_format($categoryPurchaseExpense->quantity, 2) }} </td>
                                <td class="has-text-right"> {{ number_format($categoryPurchaseExpense->expense, 2) }} </td>
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
                            <i class="fas fa-money-check-dollar"></i>
                        </span>
                        <span>Purchases by Payment Methods</span>
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
                        @foreach ($supplierReport->getPaymentTypesByPurchase as $paymentTypeByPurchase)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $paymentTypeByPurchase->payment_type }} </td>
                                <td class="has-text-right"> {{ $paymentTypeByPurchase->transactions }} </td>
                                <td class="has-text-right"> {{ number_format($paymentTypeByPurchase->expense, 2) }} </td>
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
                            <i class="fas fa-shopping-bag"></i>
                        </span>
                        <span>Purchases by Types</span>
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
                        <th><abbr> Purchase Type </abbr></th>
                        <th class="has-text-right"><abbr> Transactions </abbr></th>
                        <th class="has-text-right"><abbr> Expense </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($supplierReport->getPurchasesByType as $purchasesByType)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $purchasesByType->type }} </td>
                                <td class="has-text-right"> {{ $purchasesByType->transactions }} </td>
                                <td class="has-text-right"> {{ number_format($purchasesByType->expense, 2) }} </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>
    </div>

    <h1 class="mx-3 m-lr-0 mt-5 text-green has-text-weight-medium is-size-6-mobile">
        <span class="icon">
            <i class="fas fa-filter"></i>
        </span>
        <span>
            Filtered Expenses Report
        </span>
    </h1>

    <div class="columns is-marginless is-multiline">
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                amount="{{ number_format($expenseReport->getTotalExpenseAfterTax, 2) }}"
                border-color="#fff"
                text-color="text-purple"
                label="Expense After VAT/TOT"
            ></x-common.index-insight>
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                label-text-size="is-size-6"
                :amount="number_format($expenseReport->getTotalExpenseBeforeTax, 2)"
                border-color="#fff"
                text-color="text-green"
                label="Expense Before VAT/TOT"
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
    </div>

    <div class="columns is-marginless is-multiline">
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
    </div>
@endsection
