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
    <style>
        .page-break {
            page-break-inside: avoid;
        }

        @media print {
            .table-breaked {
                page-break-before: auto;
            }
        }

        .summernote-table table td,
        .summernote-table table th {
            border-width: 1px !important;
            padding: 0 !important;
        }

        td {
            padding-top: 0.25rem !important;
            padding-bottom: 0.25rem !important;
        }
    </style>
</head>

<body>
    <x-print.header :warehouse="$sale->warehouse" />

    <main>
        <x-print.customer :customer="$sale->customer ?? ''" />

        <hr class="my-0">

        <section class="is-clearfix has-background-white-bis pl-6 py-3">
            <aside
                class="is-pulled-left"
                style="width: 25% !important"
            >
                <h1 class="is-uppercase has-text-grey-light has-text-weight-bold is-size-7">
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
                <h1 class="is-uppercase has-text-grey-light has-text-weight-bold is-size-7">
                    FS N<u>o</u>
                </h1>
                <h1 class="has-text-black is-size-6 pr-2">
                    {{ $sale->fs_no ?? '-' }}
                </h1>
            </aside>
            <aside
                class="is-pulled-left"
                style="width: 25% !important"
            >
                <h1 class="is-uppercase has-text-grey-light has-text-weight-bold is-size-7">
                    Issued On
                </h1>
                <h1 class="has-text-black is-size-6 pr-2">
                    {{ $sale->issued_on->toFormattedDateString() }}
                </h1>
            </aside>
        </section>

        <section class="is-clearfix has-background-white-bis pl-6 py-3">
            <aside
                class="is-pulled-left"
                style="width: 25% !important"
            >
                <h1 class="is-uppercase has-text-grey-light has-text-weight-bold is-size-7">
                    Payment Type
                </h1>
                <h1 class="has-text-black is-size-6 pr-2">
                    {{ $sale->payment_type }}
                </h1>
            </aside>
            <aside
                class="is-pulled-left"
                style="width: 25% !important"
            >
                <h1 class="is-uppercase has-text-grey-light has-text-weight-bold is-size-7">
                    Cash Amount
                </h1>
                <h1 class="has-text-black is-size-6 pr-2">
                    {{ number_format($sale->payment_in_cahs, 2) }}
                    ({{ number_format($sale->cashPayableInPercentage, 2) }}%)
                </h1>
            </aside>
            @if (!$sale->isPaymentInCash())
                <aside
                    class="is-pulled-left"
                    style="width: 25% !important"
                >
                    <h1 class="is-uppercase has-text-grey-light has-text-weight-bold is-size-7">
                        Credit Amount
                    </h1>
                    <h1 class="has-text-black is-size-6 pr-2">
                        {{ number_format($sale->payment_in_credit, 2) }}
                        ({{ number_format($sale->creditPayableInPercentage, 2) }}%)
                    </h1>
                </aside>
            @endif
        </section>

        <section class="pt-5 has-text-centered">
            <h1 class="is-uppercase has-text-grey-dark has-text-weight-bold is-size-4 is-underlined">
                {{ $sale->isPaymentInCash() ? 'Cash' : 'Credit' }} Sales Attachment
            </h1>
        </section>

        <section class="px-6 table-breaked">
            <table class="table is-bordered is-hoverable is-fullwidth is-narrow is-size-7">
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
                        <th>Code</th>
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
                            <td> {{ $saleDetail->product->code ?? '-' }} </td>
                            <td class="has-text-right"> {{ number_format($saleDetail->quantity, 2) }} </td>
                            <td class="has-text-centered"> {{ $saleDetail->product->unit_of_measurement }} </td>
                            <td class="has-text-right"> {{ number_format($saleDetail->unit_price, 2) }} </td>
                            <td class="has-text-right"> {{ number_format($saleDetail->totalPrice, 2) }} </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td
                            colspan="5"
                            class="is-borderless"
                        ></td>
                        <td class="has-text-weight-bold">Sub-Total</td>
                        <td class="has-text-right">{{ number_format($sale->subtotalPrice, 2) }}</td>
                    </tr>
                    <tr>
                        <td
                            colspan="5"
                            class="is-borderless"
                        ></td>
                        <td class="has-text-weight-bold">VAT 15%</td>
                        <td class="has-text-right">{{ number_format($sale->vat, 2) }}</td>
                    </tr>
                    <tr>
                        <td
                            colspan="5"
                            class="is-borderless"
                        ></td>
                        <td class="has-text-weight-bold">Grand Total</td>
                        <td class="has-text-right has-text-weight-bold">{{ number_format($sale->grandTotalPrice, 2) }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="has-text-right is-size-7 has-text-weight-bold mt-5">
                This receipt is not valid unless the fiscal receipt is attached!
            </div>
        </section>
    </main>

    <div style="margin-bottom: 27% !important">&nbsp;</div>

    <footer style="position:absolute;bottom: 14%;left: 0;right: 0;">
        <aside class="pl-6 my-5">
            <h1 class="title is-size-7 is-uppercase mb-6">
                I received the above goods/services in good condition
                <br>
            </h1>
            <div style="border: 1px solid lightgrey;width: 50%"></div>
        </aside>
    </footer>

    @if ($sale->createdBy->is($sale->approvedBy))
        <footer style="position:absolute;bottom: 0%;left: 0;right: 0;">
            <aside class="pl-6">
                <h1 class="is-size-7 is-uppercase has-text-grey-light mt-3">
                    Prepared & Approved By
                </h1>
                <h1 class="has-text-weight-bold has-text-grey-dark is-capitalized">
                    {{ $sale->createdBy->name }}
                </h1>
                <h1 class="title is-size-7 is-uppercase has-text-grey-light mb-4 mt-5">
                    <div>
                        Signature
                    </div>
                    <div
                        class="mt-6"
                        style="border: 1px solid lightgrey;width: 30%"
                    ></div>
                </h1>
            </aside>
        </footer>
    @else
        <footer style="position:absolute;bottom: 0%;left: 0;right: 0;">
            <aside class="pl-6">
                <h1 class="is-size-7 is-uppercase has-text-grey-light mt-3">
                    Prepared By
                </h1>
                <h1 class="has-text-weight-bold has-text-grey-dark is-capitalized">
                    {{ $sale->createdBy->name }}
                </h1>
                <h1 class="title is-size-7 is-uppercase has-text-grey-light mb-4 mt-5">
                    <div>
                        Signature
                    </div>
                    <div
                        class="mt-6"
                        style="border: 1px solid lightgrey;width: 30%"
                    ></div>
                </h1>
            </aside>
        </footer>
        <footer style="position:absolute;bottom: 0%;left: 15%;right: 0;margin-left: 40%">
            <aside class="pl-6">
                <h1 class="is-size-7 is-uppercase has-text-grey-light mt-3">
                    Approved By
                </h1>
                <h1 class="has-text-weight-bold has-text-grey-dark is-capitalized">
                    {{ $sale->approvedBy->name ?? 'Not Approved' }}
                </h1>
                <h1 class="title is-size-7 is-uppercase has-text-grey-light mb-4 mt-5">
                    <div>
                        Signature
                    </div>
                    <div
                        class="mt-6"
                        style="border: 1px solid lightgrey;width: 70%"
                    ></div>
                </h1>
            </aside>
        </footer>
    @endif
</body>

</html>
