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
    <title> Return Voucher #{{ $return->code }} - {{ userCompany()->name }} </title>
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
        <x-print.customer :customer="$return->customer ?? ''" />

        <hr class="my-0">

        <section class="is-clearfix has-background-white-bis pl-6 py-3">
            <aside
                class="is-pulled-left"
                style="width: 25% !important"
            >
                <h1 class="is-uppercase has-text-grey-light has-text-weight-bold is-size-7">
                    N<u>o</u>
                </h1>
                <h1 class="has-text-black is-size-6 pr-2">
                    {{ $return->code }}
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
                    {{ $return->issued_on->toFormattedDateString() }}
                </h1>
            </aside>
        </section>

        <section class="pt-5 has-text-centered">
            <h1 class="is-uppercase has-text-grey-dark has-text-weight-bold is-size-4 is-underlined">
                Return Voucher
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
                    @foreach ($return->returnDetails as $returnDetail)
                        <tr>
                            <td class="has-text-centered"> {{ $loop->index + 1 }} </td>
                            <td> {{ $returnDetail->product->name }} </td>
                            <td> {{ $returnDetail->product->code ?? '-' }} </td>
                            <td class="has-text-right"> {{ number_format($returnDetail->quantity, 2) }} </td>
                            <td class="has-text-centered"> {{ $returnDetail->product->unit_of_measurement }} </td>
                            <td class="has-text-right"> {{ number_format($returnDetail->unit_price, 2) }} </td>
                            <td class="has-text-right"> {{ number_format($returnDetail->totalPrice, 2) }} </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td
                            colspan="5"
                            class="is-borderless"
                        ></td>
                        <td class="has-text-weight-bold">Sub-Total</td>
                        <td class="has-text-right">{{ number_format($return->subtotalPrice, 2) }}</td>
                    </tr>
                    <tr>
                        <td
                            colspan="5"
                            class="is-borderless"
                        ></td>
                        <td class="has-text-weight-bold">VAT 15%</td>
                        <td class="has-text-right">{{ number_format($return->vat, 2) }}</td>
                    </tr>
                    <tr>
                        <td
                            colspan="5"
                            class="is-borderless"
                        ></td>
                        <td class="has-text-weight-bold">Grand Total</td>
                        <td class="has-text-right has-text-weight-bold">{{ number_format($return->grandTotalPrice, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>
    @if ($return->createdBy->is($return->approvedBy))
        <footer
            class="has-background-white-ter"
            style="position:absolute;bottom: 0%;left: 0;right: 0;margin-top: 20%"
        >
            <aside class="pl-6">
                <h1 class="is-size-7 is-uppercase has-text-grey-light mt-3">
                    Prepared & Approved By
                </h1>
                <h1 class="has-text-weight-bold has-text-grey-dark is-capitalized">
                    {{ $return->createdBy->name }}
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
                    {{ $return->createdBy->name }}
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
                    {{ $return->approvedBy->name }}
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
