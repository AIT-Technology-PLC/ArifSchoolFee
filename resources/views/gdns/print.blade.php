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
    <title> DO #{{ $gdn->code }} - {{ userCompany()->name }} </title>
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
    <x-print.header />

    <main>
        <x-print.branch :warehouse="$gdn->warehouse" />

        <x-print.customer :customer="$gdn->customer ?? ''" />

        <hr class="my-0">

        <section class="is-clearfix has-background-white-bis pl-6 py-3">
            <aside
                class="is-pulled-left"
                style="width: 33.3% !important"
            >
                <h1 class="is-uppercase has-text-grey-light has-text-weight-bold is-size-7">
                    N<u>o</u>
                </h1>
                <h1 class="has-text-black is-size-6 is-capitalized">
                    {{ $gdn->code }}
                </h1>
            </aside>
            <aside
                class="is-pulled-left"
                style="width: 33.3% !important"
            >
                <h1 class="is-uppercase has-text-grey-light has-text-weight-bold is-size-7">
                    Issued On
                </h1>
                <h1 class="has-text-black is-size-6 is-capitalized">
                    {{ $gdn->issued_on->toFormattedDateString() }}
                </h1>
            </aside>
            <aside
                class="is-pulled-left"
                style="width: 33.3% !important"
            >
                <h1 class="is-uppercase has-text-grey-light has-text-weight-bold is-size-7">
                    Payment Type
                </h1>
                <h1 class="has-text-black is-size-6 is-capitalized">
                    {{ $gdn->payment_type }}
                </h1>
            </aside>
        </section>

        <section class="pt-5 has-text-centered">
            <h1 class="is-uppercase has-text-grey-dark has-text-weight-bold is-size-4 is-underlined">
                Delivery Note
            </h1>
        </section>

        <section class="px-6 table-breaked">
            <table class="table is-bordered is-hoverable is-fullwidth is-narrow is-size-7">
                <thead>
                    <tr class="is-borderless">
                        <td
                            colspan="{{ userCompany()->isDiscountBeforeVAT() ? 7 : 6 }}"
                            class="is-borderless"
                        >&nbsp;</td>
                    </tr>
                    <tr class="is-borderless">
                        <td
                            colspan="{{ userCompany()->isDiscountBeforeVAT() ? 7 : 6 }}"
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
                        @if (userCompany()->isDiscountBeforeVAT())
                            <th>Discount</th>
                        @endif
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($gdn->gdnDetails as $gdnDetail)
                        <tr>
                            <td class="has-text-centered"> {{ $loop->index + 1 }} </td>
                            <td>
                                {{ $gdnDetail->product->name }}
                            </td>
                            <td> {{ $gdnDetail->product->code ?? '-' }} </td>
                            <td class="has-text-right"> {{ number_format($gdnDetail->quantity, 2) }} </td>
                            <td class="has-text-centered"> {{ $gdnDetail->product->unit_of_measurement }} </td>
                            <td class="has-text-right"> {{ number_format($gdnDetail->unit_price, 2) }} </td>
                            @if (userCompany()->isDiscountBeforeVAT())
                                <td class="has-text-right"> {{ number_format($gdnDetail->discount * 100, 2) }}% </td>
                            @endif
                            <td class="has-text-right"> {{ number_format($gdnDetail->totalPrice, 2) }} </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td
                            colspan="{{ userCompany()->isDiscountBeforeVAT() ? 6 : 5 }}"
                            class="is-borderless"
                        ></td>
                        <td class="has-text-weight-bold">Sub-Total</td>
                        <td class="has-text-right">{{ number_format($gdn->subtotalPrice, 2) }}</td>
                    </tr>
                    <tr>
                        <td
                            colspan="{{ userCompany()->isDiscountBeforeVAT() ? 6 : 5 }}"
                            class="is-borderless"
                        ></td>
                        <td class="has-text-weight-bold">VAT 15%</td>
                        <td class="has-text-right">{{ number_format($gdn->vat, 2) }}</td>
                    </tr>
                    <tr>
                        <td
                            colspan="{{ userCompany()->isDiscountBeforeVAT() ? 6 : 5 }}"
                            class="is-borderless"
                        ></td>
                        <td class="has-text-weight-bold">Grand Total</td>
                        <td class="has-text-right has-text-weight-bold">{{ number_format($gdn->grandTotalPrice, 2) }}</td>
                    </tr>
                    @if (!userCompany()->isDiscountBeforeVAT())
                        <tr>
                            <td
                                colspan="5"
                                class="is-borderless"
                            ></td>
                            <td class="has-text-weight-bold">Discount</td>
                            <td class="has-text-right has-text-weight-bold">{{ number_format($gdn->discount * 100, 2) }}%</td>
                        </tr>
                        <tr>
                            <td
                                colspan="5"
                                class="is-borderless"
                            ></td>
                            <td class="has-text-weight-bold">
                                Grand Total
                                <br>
                                <span class="has-text-grey">
                                    After Discount
                                </span>
                            </td>
                            <td class="has-text-right has-text-weight-bold">{{ number_format($gdn->grandTotalPriceAfterDiscount, 2) }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </section>
        <section class="pl-6 mt-5">
            <aside style="width: 40% !important">
                <table class="table is-bordered is-striped is-hoverable is-fullwidth is-size-7">
                    <tbody>
                        <tr>
                            <td
                                colspan="2"
                                class="has-text-weight-bold"
                            >Payment Details</td>
                        </tr>
                        <tr>
                            <td>
                                <p>
                                    <span class="has-text-weight-bold is-uppercase">
                                        In Cash ({{ (float) $gdn->cash_received_in_percentage }}%)
                                    </span>
                                    <br>
                                    <span>
                                        {{ number_format($gdn->paymentInCash, 2) }}
                                    </span>
                                </p>
                            </td>
                            <td>
                                <p>
                                    <span class="has-text-weight-bold is-uppercase">
                                        On Credit ({{ $gdn->credit_payable_in_percentage }}%)
                                    </span>
                                    <br>
                                    <span>
                                        {{ number_format($gdn->paymentInCredit, 2) }}
                                    </span>
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </aside>
        </section>
    </main>

    <div style="margin-bottom: 27% !important">&nbsp;</div>

    <footer
        class="has-background-white-bis"
        style="position:absolute;bottom: 14%;left: 0;right: 0;"
    >
        <aside class="pl-6 my-5">
            <h1 class="title is-size-7 is-uppercase mb-6">
                I received the above goods/services in good condition
                <br>
            </h1>
            <div style="border: 1px solid lightgrey;width: 50%"></div>
        </aside>
    </footer>
    @if ($gdn->createdBy->is($gdn->approvedBy))
        <footer
            class="has-background-white-ter"
            style="position:absolute;bottom: 0%;left: 0;right: 0;"
        >
            <aside class="pl-6">
                <h1 class="is-size-7 is-uppercase has-text-grey-light mt-3">
                    Prepared & Approved By
                </h1>
                <h1 class="has-text-weight-bold has-text-grey-dark is-capitalized">
                    {{ $gdn->createdBy->name }}
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
        <footer
            class="has-background-white-ter"
            style="position:absolute;bottom: 0%;left: 0;right: 0;"
        >
            <aside class="pl-6">
                <h1 class="is-size-7 is-uppercase has-text-grey-light mt-3">
                    Prepared By
                </h1>
                <h1 class="has-text-weight-bold has-text-grey-dark is-capitalized">
                    {{ $gdn->createdBy->name }}
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
        <footer
            class="has-background-white-ter"
            style="position:absolute;bottom: 0%;left: 15%;right: 0;margin-left: 40%"
        >
            <aside class="pl-6">
                <h1 class="is-size-7 is-uppercase has-text-grey-light mt-3">
                    Approved By
                </h1>
                <h1 class="has-text-weight-bold has-text-grey-dark is-capitalized">
                    {{ $gdn->approvedBy->name }}
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
