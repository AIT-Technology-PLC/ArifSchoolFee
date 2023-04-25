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
    <title> Invoice #{{ $sale->code }} - {{ userCompany()->name }} </title>
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
        <x-print.header :warehouse="$sale->warehouse" />
    @endif

    <hr
        class="my-0 has-background-grey-lighter"
        style="margin-left: -10%;margin-right: -10%"
    >

    <x-print.customer
        :customer="$sale->customer ?? ''"
        :contact="$sale->contact"
    />

    <section class="is-clearfix py-3">
        <aside
            class="is-pulled-left"
            style="width: 25% !important"
        >
            <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
                Invoice N<u>o</u>
            </h1>
            <h1 class="has-text-black is-size-6 pr-2">
                {{ $sale->code }}
            </h1>
        </aside>
        <aside
            class="is-pulled-left"
            style="width: 25% !important"
        >
            <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
                FS N<u>o</u>
            </h1>
            <h1 class="has-text-black is-size-6 pr-2">
                {{ !is_null($sale->fs_number) ? str()->padLeft($sale->fs_number, 8, 0) : '-' }}
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
                {{ $sale->issued_on->toFormattedDateString() }}
            </h1>
        </aside>
    </section>

    <hr
        class="my-0 has-background-grey-lighter"
        style="margin-left: -10%;margin-right: -10%"
    >

    <x-print.payment :model="$sale" />

    <section class="pt-5 has-text-centered">
        <h1 class="is-uppercase has-text-grey-dark has-text-weight-bold is-size-4 is-underlined">
            {{ !$sale->isPaymentInCredit() ? 'Cash' : 'Credit' }} Sales Attachment
        </h1>
    </section>

    <section class="table-breaked">
        <table class="table is-bordered is-hoverable is-fullwidth is-narrow is-size-7 is-transparent-color">
            <thead>
                <tr class="is-borderless">
                    <td
                        colspan="6"
                        class="is-borderless"
                    >&nbsp;</td>
                </tr>
                <tr class="is-borderless">
                    <td
                        colspan="6"
                        class="is-borderless"
                    >&nbsp;</td>
                </tr>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    @if ($havingCode)
                        <th>Code</th>
                    @endif
                    @if ($havingBatch)
                        <th>Batch No</th>
                        <th>Expiry Date</th>
                    @endif
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sale->saleDetails as $saleDetail)
                    <tr>
                        <td class="has-text-centered"> {{ $loop->index + 1 }} </td>
                        <td>
                            {{ $saleDetail->product->name }}
                        </td>
                        @if ($havingCode)
                            <td> {{ $saleDetail->product->code ?? '-' }} </td>
                        @endif
                        @if ($havingBatch)
                            <td> {{ $saleDetail->merchandiseBatch?->batch_no ?? '-' }} </td>
                            <td> {{ $saleDetail->merchandiseBatch?->expires_on?->toFormattedDateString() ?? '-' }} </td>
                        @endif
                        <td class="has-text-right"> {{ number_format($saleDetail->quantity, 2) }} </td>
                        <td class="has-text-centered"> {{ $saleDetail->product->unit_of_measurement }} </td>
                        <td class="has-text-right"> {{ number_format($saleDetail->unit_price, 2) }} </td>
                        <td class="has-text-right"> {{ number_format($saleDetail->totalPrice, 2) }} </td>
                    </tr>
                @endforeach
                <tr>
                    <td
                        colspan="{{ 4 + ($havingCode ? 1 : 0) + ($havingBatch ? 2 : 0) }}"
                        class="is-borderless"
                    ></td>
                    <td class="has-text-weight-bold">Sub-Total</td>
                    <td class="has-text-right">{{ number_format($sale->subtotalPrice, 2) }}</td>
                </tr>
                <tr>
                    <td
                        colspan="{{ 4 + ($havingCode ? 1 : 0) + ($havingBatch ? 2 : 0) }}"
                        class="is-borderless"
                    ></td>
                    <td class="has-text-weight-bold">Tax</td>
                    <td class="has-text-right">{{ number_format($sale->tax, 2) }}</td>
                </tr>
                @if ($sale->hasWithholding())
                    <tr>
                        <td
                            colspan="{{ 4 + ($havingCode ? 1 : 0) + ($havingBatch ? 2 : 0) }}"
                            class="is-borderless"
                        ></td>
                        <td class="has-text-weight-bold">Withholding Tax ({{ userCompany()->withholdingTaxes['tax_rate'] * 100 }}%)</td>
                        <td class="has-text-right">{{ number_format($sale->totalWithheldAmount, 2) }}</td>
                    </tr>
                @endif
                <tr>
                    <td
                        colspan="{{ 4 + ($havingCode ? 1 : 0) + ($havingBatch ? 2 : 0) }}"
                        class="is-borderless"
                    ></td>
                    <td class="has-text-weight-bold">Grand Total</td>
                    <td class="has-text-right has-text-weight-bold">{{ number_format($sale->grandTotalPrice, 2) }}</td>
                </tr>
                @if ($sale->hasWithholding())
                    <tr>
                        <td
                            colspan="{{ 4 + ($havingCode ? 1 : 0) + ($havingBatch ? 2 : 0) }}"
                            class="is-borderless"
                        ></td>
                        <td class="has-text-weight-bold"></td>
                        <td class="has-text-right has-text-weight-bold">{{ number_format($sale->grandTotalPrice - $sale->totalWithheldAmount, 2) }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <div class="has-text-right is-size-7 has-text-weight-bold mt-5">
            This receipt is not valid unless the fiscal receipt is attached!
        </div>
    </section>

    <footer class="my-6">
        <h1 class="title is-size-7 is-uppercase">
            I received the above goods/services in good condition:
            <span
                class="is-inline-block"
                style="border: 1px solid lightgrey;width: 20%"
            ></span>
        </h1>
    </footer>

    <x-print.user
        :created-by="$sale->createdBy ?? null"
        :approved-by="$sale->approvedBy ?? null"
    />

    <x-print.footer-marketing />
</body>

</html>
