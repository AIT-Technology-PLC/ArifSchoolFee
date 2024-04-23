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
    <title> Sales Report </title>
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

    @include('assets.print-css')
</head>

<body class="{{ $company->hasPrintTemplate() ? 'company-background company-y-padding company-x-padding' : 'px-6' }}">
    <hr
        class="my-0 has-background-grey-lighter"
        style="margin-left: -10%;margin-right: -10%"
    >

    <section class="is-clearfix py-3">
        <aside
            class="is-pulled-left"
            style="width: 50% !important"
        >
            <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
                Revenue
            </h1>
            <h1 class="has-text-black is-size-6 pr-2">
                {{ number_format($saleReport->getTotalRevenueAfterTax, 2) }}
            </h1>
        </aside>
        <aside
            class="is-pulled-left"
            style="width: 50% !important"
        >
            <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
                Volume
            </h1>
            <h1 class="has-text-black is-size-6 pr-2">
                {{ number_format($saleReport->getSalesCount) }} Sales
            </h1>
        </aside>
    </section>

    <hr
        class="my-0 has-background-grey-lighter"
        style="margin-left: -10%;margin-right: -10%"
    >

    <section class="pt-5 has-text-centered">
        <h1 class="is-uppercase has-text-grey-dark has-text-weight-bold is-size-4 is-underlined">
            Sales Report
        </h1>
        <h4>
            @if (carbon($period[0])->isSameDay(carbon($period[1])))
                {{ carbon($period[0])->toFormattedDateString() }}
            @else
                {{ carbon($period[0])->toFormattedDateString() }} - {{ carbon($period[1])->toFormattedDateString() }}
            @endif
        </h4>
    </section>

    <section class="is-clearfix py-3">
        <aside
            class="is-pulled-left mr-1"
            style="width: 45% !important"
        >
            <section class="table-breaked">
                <table class="table is-bordered is-hoverable is-fullwidth is-narrow is-size-7 is-transparent-color">
                    <thead>
                        <tr class="is-borderless">
                            <td class="is-borderless">&nbsp;</td>
                        </tr>
                        <tr class="is-borderless">
                            <td class="is-borderless">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Branch</th>
                            <th class="has-text-right">Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($saleReport->getBranchesByRevenue as $branchRevenue)
                            <tr>
                                <td> {{ $branchRevenue->branch_name }} </td>
                                <td class="has-text-right"> {{ number_format($branchRevenue->revenue, 2) }} </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
            <section class="table-breaked">
                <table class="table is-bordered is-hoverable is-fullwidth is-narrow is-size-7 is-transparent-color">
                    <thead>
                        <tr class="is-borderless">
                            <td class="is-borderless">&nbsp;</td>
                        </tr>
                        <tr class="is-borderless">
                            <td class="is-borderless">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Payment Method</th>
                            <th class="has-text-right">Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($saleReport->getPaymentTypesByRevenue as $paymentTypeRevenue)
                            <tr>
                                <td> {{ $paymentTypeRevenue->payment_type }} </td>
                                <td class="has-text-right"> {{ number_format($paymentTypeRevenue->revenue, 2) }} </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        </aside>
        <aside
            class="is-pulled-left"
            style="width: 45% !important;margin-left: 10% !important"
        >
            <section class="table-breaked">
                <table class="table is-bordered is-hoverable is-fullwidth is-narrow is-size-7 is-transparent-color">
                    <thead>
                        <tr class="is-borderless">
                            <td class="is-borderless">&nbsp;</td>
                        </tr>
                        <tr class="is-borderless">
                            <td class="is-borderless">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <th class="has-text-right">Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($saleReport->getProductCategoriesByRevenue as $categoryRevenue)
                            <tr>
                                <td> {{ $categoryRevenue->product_category_name }} </td>
                                <td class="has-text-right"> {{ number_format($categoryRevenue->revenue, 2) }} </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        </aside>
    </section>
    <section class="table-breaked mt-6">
        <table class="table is-bordered is-hoverable is-fullwidth is-narrow is-size-7 is-transparent-color">
            <thead>
                <tr>
                    <th><abbr> Product </abbr></th>
                    <th class="has-text-right"><abbr> Quantity </abbr></th>
                    <th class="has-text-right"><abbr> Revenue </abbr></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($saleReport->getProductsByRevenue as $productRevenue)
                    <tr>
                        <td> {{ $productRevenue->product_name }} </td>
                        <td class="has-text-right"> {{ quantity($productRevenue->quantity, $productRevenue->product_unit_of_measurement) }} </td>
                        <td class="has-text-right"> {{ number_format($productRevenue->revenue, 2) }} </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
</body>

</html>
