<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >
    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >
    <title> Profit Report - {{ userCompany()->name }} </title>
    <link
        rel="shortcut icon"
        type="image/png"
        href="{{ asset('img/favicon.png') }}"
    />
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css"
        integrity="sha256-WLKGWSIJYerRN8tbNGtXWVYnUM5wMJTXD8eG4NtGcDM="
        crossorigin="anonymous"
    >
    <link
        href="{{ asset('css/app.css') }}"
        rel="stylesheet"
    >

    @include('assets.print-css', ['company' => userCompany()])
</head>

<body class="{{ userCompany()->hasPrintTemplate() ? 'company-background company-y-padding company-x-padding' : 'px-6' }}">
    @if (!userCompany()->hasPrintTemplate())
        <x-print.header />
    @endif

    <hr
        class="my-0 has-background-grey-lighter"
        style="margin-left: -10%;margin-right: -10%"
    >

    <section class="has-text-centered mt-6">
        <div class="title is-4 is-uppercase has-text-weight-bold">
            Profit & Loss Report
        </div>
        <div>
            For the Period: {{ carbon($filters['period'][0])->toFormattedDateString() }} - {{ carbon($filters['period'][1])->toFormattedDateString() }}
        </div>
    </section>

    <section class="mt-6">
        <div
            class="heading is-size-4 has-text-white has-text-weight-bold py-2 pl-3"
            style="background-color: grey"
        >
            INCOME
        </div>
    </section>

    <section class="is-clearfix mt-3 has-text-black is-size-5 mx-5">
        <aside
            class="is-pulled-left"
            style="width: 50% !important"
        >
            <h1>
                Sales
            </h1>
        </aside>
        <aside
            class="is-pulled-left has-text-right"
            style="width: 50% !important"
        >
            <h1 class="has-text-weight-bold is-underlined">
                {{ number_format($profitReport->getTotalRevenueBeforeTax, 2) }}
            </h1>
        </aside>
    </section>

    <section class="mt-6">
        <div
            class="heading is-size-4 has-text-white has-text-weight-bold py-2 pl-3"
            style="background-color: grey"
        >
            COST OF GOODS SOLD
        </div>
    </section>


    <section class="is-clearfix mt-3 has-text-black is-size-5 mx-5">
        <aside
            class="is-pulled-left"
            style="width: 50% !important"
        >
            <h1>
                Beginning Inventory
            </h1>
        </aside>
        <aside
            class="is-pulled-left has-text-right"
            style="width: 50% !important"
        >
            <h1>
                {{ number_format($profitReport->getBeginningInventoryCost, 2) }}
            </h1>
        </aside>
    </section>

    <section class="is-clearfix mt-3 has-text-black is-size-5 mx-5">
        <aside
            class="is-pulled-left"
            style="width: 50% !important"
        >
            <h1>
                New Inventory
            </h1>
        </aside>
        <aside
            class="is-pulled-left has-text-right"
            style="width: 50% !important"
        >
            <h1>
                {{ number_format($profitReport->getNewCosts, 2) }}
            </h1>
        </aside>
    </section>

    <section class="is-clearfix mt-3 has-text-black is-size-5 mx-5">
        <aside
            class="is-pulled-left"
            style="width: 50% !important"
        >
            <h1>
                Cost of Goods Available for Sale
            </h1>
        </aside>
        <aside
            class="is-pulled-left has-text-right"
            style="width: 50% !important"
        >
            <h1 class="has-text-weight-bold is-underlined">
                {{ number_format($profitReport->getCostOfGoodsAvailableForSale, 2) }}
            </h1>
        </aside>
    </section>

    <section class="is-clearfix mt-3 has-text-black is-size-5 mx-5">
        <aside
            class="is-pulled-left"
            style="width: 50% !important"
        >
            <h1>
                Less: Ending Inventory
            </h1>
        </aside>
        <aside
            class="is-pulled-left has-text-right"
            style="width: 50% !important"
        >
            <h1>
                {{ number_format($profitReport->getEndingInventoryCost, 2) }}
            </h1>
        </aside>
    </section>

    <section class="is-clearfix mt-3 has-text-black is-size-5 mx-5">
        <aside
            class="is-pulled-left"
            style="width: 50% !important"
        >
            <h1 class="has-text-weight-bold is-uppercase">
                Cost of Goods Sold
            </h1>
        </aside>
        <aside
            class="is-pulled-left has-text-right is-underlined"
            style="width: 50% !important"
        >
            <h1 class="has-text-weight-bold">
                {{ number_format($profitReport->getCostOfGoodsSold, 2) }}
            </h1>
        </aside>
    </section>

    <hr
        class="has-background-grey-lighter mt-6"
        style="margin-left: -10%;margin-right: -10%"
    >

    <section class="is-clearfix mt-3 has-text-black is-size-5 mx-5 mt-6">
        <aside
            class="is-pulled-left"
            style="width: 50% !important"
        >
            <h1 class="has-text-weight-bold is-uppercase">
                Gross Profit
            </h1>
        </aside>
        <aside
            class="is-pulled-left has-text-right is-underlined"
            style="width: 50% !important"
        >
            <h1 class="has-text-weight-bold">
                {{ number_format($profitReport->getProfit, 2) }}
            </h1>
        </aside>
    </section>

    <div class="mt-6 ml-3">
        <x-print.user
            :created-by="authUser()"
            :approved-by="null"
        />
    </div>

    <x-print.footer-marketing />
</body>

</html>
