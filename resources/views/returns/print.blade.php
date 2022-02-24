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
    <header class="is-clearfix pt-5 has-background-white-ter">
        <aside class="is-pulled-left ml-6 mt-5 pt-4">
            <img
                src="{{ asset('storage/' . $return->company->logo) }}"
                style="width: 300px !important; height: 130px !important"
            >
        </aside>
        <aside class="is-pulled-right mr-6">
            <h1 class="heading is-capitalized has-text-black has-text-weight-medium is-size-5">
                {{ $return->company->name }}
            </h1>
            <h1 class="is-uppercase has-text-grey-light has-text-weight-dark is-size-7 mb-0">
                Tel/Phone
            </h1>
            <p class="has-text-grey-dark has-text-weight-medium is-size-6">
                {{ $return->company->phone ?? '-' }}
            </p>
            <h1 class="is-uppercase has-text-grey-light has-text-weight-dark is-size-7">
                Email
            </h1>
            <p class="has-text-grey-dark has-text-weight-medium is-size-6">
                {{ $return->company->email ?? '-' }}
            </p>
            <h1 class="is-uppercase has-text-grey-light has-text-weight-dark is-size-7">
                Address
            </h1>
            <p class="has-text-grey-dark has-text-weight-medium is-size-6">
                {{ $return->company->address ?? '-' }}
            </p>
        </aside>
    </header>

    <main>
        @if ($return->customer)
            <section class="pt-5 pb-3 has-background-white-bis">
                <aside class="ml-6">
                    <h1 class="is-uppercase has-text-grey-light has-text-weight-dark is-size-7">
                        Customer
                    </h1>
                    <h1 class="has-text-weight-bold has-text-grey-dark is-size-6 is-capitalized">
                        {{ $return->customer->company_name ?? '-' }}
                    </h1>
                    @if ($return->customer->tin)
                        <h1 class="has-text-weight-normal has-text-grey-dark is-size-6 is-capitalized">
                            {{ $return->customer->tin ?? '-' }}
                        </h1>
                    @endif
                    @if ($return->customer->address)
                        <h1 class="has-text-weight-normal has-text-grey-dark is-size-6 is-capitalized">
                            {{ $return->customer->address ?? '-' }}
                        </h1>
                    @endif
                </aside>
            </section>
        @endif

        <section class="is-clearfix has-background-white-bis pl-6 py-3">
            <aside
                class="is-pulled-left"
                style="width: 33.3% !important"
            >
                <h1 class="is-uppercase has-text-grey-light has-text-weight-dark is-size-7">
                    N<u>o</u>
                </h1>
                <h1 class="has-text-weight-bold has-text-grey-dark is-size-6 is-capitalized">
                    {{ $return->code }}
                </h1>
            </aside>
            <aside
                class="is-pulled-left"
                style="width: 33.3% !important"
            >
                <h1 class="is-uppercase has-text-grey-light has-text-weight-dark is-size-7">
                    Issued On
                </h1>
                <h1 class="has-text-weight-bold has-text-grey-dark is-size-6 is-capitalized">
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
                        <th>Product Description</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($return->returnDetails as $returnDetail)
                        <tr>
                            <td class="has-text-centered"> {{ $loop->index + 1 }} </td>
                            <td> {{ $returnDetail->product->name }} </td>
                            <td> {{ $returnDetail->product->productCategory->name ?? '' }} </td>
                            <td class="has-text-right"> {{ number_format($returnDetail->quantity, 2) }} {{ $returnDetail->product->unit_of_measurement ?? '' }} </td>
                            <td class="has-text-right"> {{ number_format($returnDetail->unit_price, 2) }} </td>
                            <td class="has-text-right"> {{ number_format($returnDetail->totalPrice, 2) }} </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td
                            colspan="4"
                            class="is-borderless"
                        ></td>
                        <td class="has-text-weight-bold">Sub-Total</td>
                        <td class="has-text-right">{{ number_format($return->subtotalPrice, 2) }}</td>
                    </tr>
                    <tr>
                        <td
                            colspan="4"
                            class="is-borderless"
                        ></td>
                        <td class="has-text-weight-bold">VAT 15%</td>
                        <td class="has-text-right">{{ number_format($return->vat, 2) }}</td>
                    </tr>
                    <tr>
                        <td
                            colspan="4"
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
