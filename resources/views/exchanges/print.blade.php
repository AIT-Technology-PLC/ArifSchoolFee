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
    <title> Exchage Voucher #{{ $exchange->exchangeable->code }} - {{ userCompany()->name }} </title>
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
        <x-print.header :warehouse="$exchange->exchangeable->warehouse" />
    @endif

    <hr
        class="my-0 has-background-grey-lighter"
        style="margin-left: -10%;margin-right: -10%"
    >

    <x-print.customer :customer="$exchange->exchangeable->customer ?? ($exchange->exchangeable->customer ?? '')" />

    <section class="is-clearfix py-3">
        <aside
            class="is-pulled-left"
            style="width: 25% !important"
        >
            <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
                Sale N<u>o</u>
            </h1>
            <h1 class="has-text-black is-size-6 pr-2">
                {{ $exchange->exchangeable->code }}
            </h1>
        </aside>
        <aside
            class="is-pulled-left"
            style="width: 25% !important"
        >
            <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
                Exchange No
            </h1>
            <h1 class="has-text-black is-size-6 pr-2">
                {{ $exchange->exchangeable->code ?? 'N/A' }}
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
                {{ $exchange->exchangeable->issued_on->toFormattedDateString() }}
            </h1>
        </aside>
        {{-- @foreach ($exchange->CustomFields(1) as $field)
            <aside
                class="is-pulled-left"
                style="width: 25% !important"
            >
                <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
                    {{ $field->customField->label }}
                </h1>
                <h1 class="has-text-black is-size-6 pr-2">
                    {{ $field->value }}
                </h1>
            </aside>
        @endforeach --}}
    </section>

    <section class="pt-5 has-text-centered">
        <h1 class="is-uppercase has-text-grey-dark has-text-weight-bold is-size-4 is-underlined">
            Exchange Voucher
        </h1>
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
                    <th>#</th>
                    <th>Product</th>
                    @if (userCompany()->showProductCodeOnPrintouts())
                        <th>Code</th>
                    @endif
                    <th>Quantity</th>
                    <th>Returned Quantity</th>
                    <th>Unit</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($exchange->exchangeDetails as $exchangeDetail)
                    <tr>
                        <td class="has-text-centered"> {{ $loop->index + 1 }} </td>
                        <td> {{ $exchangeDetail->product->name }} </td>
                        @if (userCompany()->showProductCodeOnPrintouts())
                            <td> {{ $exchangeDetail->product->code ?? '-' }} </td>
                        @endif
                        <td class="has-text-right"> {{ number_format($exchangeDetail->quantity, 2) }} </td>
                        <td class="has-text-right"> {{ number_format($exchangeDetail->returned_quantity, 2) }} </td>
                        <td class="has-text-centered"> {{ $exchangeDetail->product->unit_of_measurement }} </td>
                        <td class="has-text-right"> {{ number_format($exchangeDetail->unit_price, 2) }} </td>
                        <td class="has-text-right"> {{ number_format($exchangeDetail->totalPrice, 2) }} </td>
                    </tr>
                @endforeach
                <tr>
                    <td
                        colspan="{{ userCompany()->showProductCodeOnPrintouts() ? 5 : 4 }}"
                        class="is-borderless"
                    ></td>
                    <td class="has-text-weight-bold">Sub-Total</td>
                    <td class="has-text-right">{{ number_format($exchange->subtotalPrice, 2) }}</td>
                </tr>
                <tr>
                    <td
                        colspan="{{ userCompany()->showProductCodeOnPrintouts() ? 5 : 4 }}"
                        class="is-borderless"
                    ></td>
                    <td class="has-text-weight-bold">Tax</td>
                    <td class="has-text-right">{{ number_format($exchange->tax, 2) }}</td>
                </tr>
                <tr>
                    <td
                        colspan="{{ userCompany()->showProductCodeOnPrintouts() ? 5 : 4 }}"
                        class="is-borderless"
                    ></td>
                    <td class="has-text-weight-bold">Grand Total</td>
                    <td class="has-text-right has-text-weight-bold">{{ number_format($exchange->grandTotalPrice, 2) }}</td>
                </tr>
                <tr>
                    <td
                        colspan="{{ userCompany()->showProductCodeOnPrintouts() ? 5 : 4 }}"
                        class="is-borderless"
                    ></td>
                    <td class="has-text-weight-bold">Sale Grand Total</td>
                    <td class="has-text-right has-text-weight-bold">{{ number_format($exchange->exchangeable->grandTotalPrice, 2) }}</td>
                </tr>
                <tr>
                    <td
                        colspan="{{ userCompany()->showProductCodeOnPrintouts() ? 5 : 4 }}"
                        class="is-borderless"
                    ></td>
                    <td class="has-text-weight-bold">Return Grand Total</td>
                    <td class="has-text-right has-text-weight-bold">{{ number_format($exchange->returnn->grandTotalPrice, 2) }}</td>
                </tr>
                <tr>
                    <td
                        colspan="{{ userCompany()->showProductCodeOnPrintouts() ? 5 : 4 }}"
                        class="is-borderless"
                    ></td>
                    @if (number_format($exchange->returnn->grandTotalPrice, 2) > number_format($exchange->exchangeable->grandTotalPrice, 2))
                        <td class="has-text-weight-bold">Amount Payable</td>
                        <td class="has-text-right has-text-weight-bold">{{ number_format($exchange->returnn->grandTotalPrice, 2) - number_format($exchange->exchangeable->grandTotalPrice, 2) }}</td>
                    @endif
                    @if (number_format($exchange->returnn->grandTotalPrice, 2) < number_format($exchange->exchangeable->grandTotalPrice, 2))
                        <td class="has-text-weight-bold">Amount Receivable </td>
                        <td class="has-text-right has-text-weight-bold">{{ number_format($exchange->exchangeable->grandTotalPrice, 2) - number_format($exchange->returnn->grandTotalPrice, 2) }}</td>
                    @endif
                </tr>
            </tbody>
        </table>
    </section>

    <section class="mt-6">
        <x-print.user
            :created-by="$exchange->createdBy ?? null"
            :approved-by="$exchange->approvedBy ?? null"
        />
    </section>
    <x-print.footer-marketing />
</body>
</html>
