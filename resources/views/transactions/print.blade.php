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
    <title> {{ $transaction->pad->abbreviation }} #{{ $transaction->code }} - {{ userCompany()->name }} </title>
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

<body class="{{ userCompany()->hasPrintTemplate() ? 'company-background company-y-padding company-x-padding' : 'px-6' }}">
    @if (!userCompany()->hasPrintTemplate())
        <x-print.header :warehouse="$transaction->warehouse" />
    @endif

    <hr
        class="my-0 has-background-grey-lighter"
        style="margin-left: -10%;margin-right: -10%"
    >

    <x-print.customer :customer="$transaction->customer ?? ''" />

    <section class="is-clearfix py-3">
        <aside
            class="is-pulled-left"
            style="width: 25% !important"
        >
            <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
                N<u>o</u>
            </h1>
            <h1 class="has-text-black is-size-6 pr-2">
                {{ $transaction->code }}
            </h1>
        </aside>
        <aside
            class="is-pulled-left"
            style="width: 25% !important"
        >
            <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
                Issued On
            </h1>
            <h1 class="has-text-black is-size-6 pr-2">
                {{ $transaction->issued_on->toFormattedDateString() }}
            </h1>
        </aside>
        @if ($transaction->payment_type)
            <aside
                class="is-pulled-left"
                style="width: 25% !important"
            >
                <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
                    Payment Type
                </h1>
                <h1 class="has-text-black is-size-6 pr-2">
                    {{ $transaction->payment_type }}
                </h1>
            </aside>
        @endif
    </section>

    <section class="pt-5 has-text-centered">
        <h1 class="is-uppercase has-text-grey-dark has-text-weight-bold is-size-4 is-underlined">
            {{ $transaction->pad->name }}
        </h1>
    </section>

    <section class="table-breaked">
        <table class="table is-bordered is-hoverable is-fullwidth is-narrow is-size-7 is-transparent-color">
            <thead>
                <tr class="is-borderless">
                    <td
                        colspan="{{ userCompany()->isDiscountBeforeVAT() ? count($columns) + 1 : count($columns) }}"
                        class="is-borderless"
                    >&nbsp;</td>
                </tr>
                <tr class="is-borderless">
                    <td
                        colspan="{{ userCompany()->isDiscountBeforeVAT() ? count($columns) + 1 : count($columns) }}"
                        class="is-borderless"
                    >&nbsp;</td>
                </tr>
                <tr>
                    <th>#</th>
                    @foreach ($columns as $column)
                        @continue (!userCompany()->isDiscountBeforeVAT() && $column == 'discount')
                        <th>{{ str($column)->replace('_', ' ')->title() }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($transaction->transactionDetails as $detail)
                    <tr>
                        <th>
                            {{ $loop->iteration }}
                        </th>
                        @foreach ($columns as $column)
                            @continue (!userCompany()->isDiscountBeforeVAT() && $column == 'discount')
                            <td>
                                {{ $detail[$column] }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
                @if ($transaction->pad->hasPrices())
                    <tr>
                        <td
                            colspan="{{ userCompany()->isDiscountBeforeVAT() ? count($columns) - 1 : count($columns) - 2 }}"
                            class="is-borderless"
                        ></td>
                        <td class="has-text-weight-bold">Sub-Total</td>
                        <td class="has-text-right">{{ number_format($transaction->subtotalPrice, 2) }}</td>
                    </tr>
                    <tr>
                        <td
                            colspan="{{ userCompany()->isDiscountBeforeVAT() ? count($columns) - 1 : count($columns) - 2 }}"
                            class="is-borderless"
                        ></td>
                        <td class="has-text-weight-bold">VAT 15%</td>
                        <td class="has-text-right">{{ number_format($transaction->vat, 2) }}</td>
                    </tr>
                    <tr>
                        <td
                            colspan="{{ userCompany()->isDiscountBeforeVAT() ? count($columns) - 1 : count($columns) - 2 }}"
                            class="is-borderless"
                        ></td>
                        <td class="has-text-weight-bold">Grand Total</td>
                        <td class="has-text-right has-text-weight-bold">{{ number_format($transaction->grandTotalPrice, 2) }}</td>
                    </tr>
                    @if (!userCompany()->isDiscountBeforeVAT())
                        <tr>
                            <td
                                colspan="{{ userCompany()->isDiscountBeforeVAT() ? count($columns) - 1 : count($columns) - 2 }}"
                                class="is-borderless"
                            ></td>
                            <td class="has-text-weight-bold">Discount</td>
                            <td class="has-text-right has-text-weight-bold">{{ number_format($transaction->discount * 100, 2) }}%</td>
                        </tr>
                        <tr>
                            <td
                                colspan="{{ userCompany()->isDiscountBeforeVAT() ? count($columns) - 1 : count($columns) - 2 }}"
                                class="is-borderless"
                            ></td>
                            <td class="has-text-weight-bold">
                                Grand Total
                                <br>
                                <span class="has-text-grey">
                                    After Discount
                                </span>
                            </td>
                            <td class="has-text-right has-text-weight-bold">{{ number_format($transaction->grandTotalPriceAfterDiscount, 2) }}</td>
                        </tr>
                    @endif
                @endif
            </tbody>
        </table>
    </section>

    <footer class="my-6">
        @if ($transaction->pad->isInventoryOperationSubtract() || $transaction->customer)
            <h1 class="title is-size-7 is-uppercase">
                I received the above goods/services in good condition:
                <span
                    class="is-inline-block"
                    style="border: 1px solid lightgrey;width: 20%"
                ></span>
            </h1>
        @endif
    </footer>

    <x-print.user
        :created-by="$transaction->createdBy ?? null"
        :approved-by="$transaction->approvedBy ?? null"
    />
</body>

</html>
