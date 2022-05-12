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
    <title> SIV #{{ $siv->code }} - {{ userCompany()->name }} </title>
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
        <section class="is-clearfix has-background-white-bis pl-6 py-3">
            <aside
                class="is-pulled-left"
                style="width: 25% !important"
            >
                <h1 class="is-uppercase has-text-grey-light has-text-weight-bold is-size-7">
                    SIV N<u>o</u>
                </h1>
                <h1 class="has-text-black is-size-6 pr-2">
                    {{ $siv->code }}
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
                    {{ $siv->issued_on->toFormattedDateString() }}
                </h1>
            </aside>
            <aside
                class="is-pulled-left"
                style="width: 25% !important"
            >
                <h1 class="is-uppercase has-text-grey-light has-text-weight-bold is-size-7">
                    Purpose
                </h1>
                <h1 class="has-text-black is-size-6 pr-2">
                    {{ $siv->purpose }}{{ $siv->ref_num ? ' No: ' . $siv->ref_num : '' }}
                </h1>
            </aside>
            @if ($siv->issued_to)
                <aside
                    class="is-pulled-left"
                    style="width: 25% !important"
                >
                    <h1 class="is-uppercase has-text-grey-light has-text-weight-bold is-size-7">
                        Issued To
                    </h1>
                    <h1 class="has-text-black is-size-6 pr-2">
                        {{ $siv->issued_to }}
                    </h1>
                </aside>
            @endif
        </section>

        <section class="pt-5 has-text-centered">
            <h1 class="is-uppercase has-text-grey-dark has-text-weight-bold is-size-4 is-underlined">
                Store Issue Voucher
            </h1>
        </section>
        <section class="px-6 table-breaked">
            <table class="table is-bordered is-hoverable is-fullwidth is-narrow is-size-7">
                <thead>
                    <tr class="is-borderless">
                        <td
                            colspan="5"
                            class="is-borderless"
                        >&nbsp;</td>
                    </tr>
                    <tr class="is-borderless">
                        <td
                            colspan="5"
                            class="is-borderless"
                        >&nbsp;</td>
                    </tr>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Code</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th>From</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($siv->sivDetails as $sivDetail)
                        <tr>
                            <td> {{ $loop->index + 1 }} </td>
                            <td> {{ $sivDetail->product->name }} </td>
                            <td> {{ $sivDetail->product->code ?? '-' }} </td>
                            <td> {{ number_format($sivDetail->quantity, 2) }} </td>
                            <td> {{ $sivDetail->product->unit_of_measurement }} </td>
                            <td> {{ $sivDetail->warehouse->name }} </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </main>

    <div style="margin-top: 36%">&nbsp;</div>

    <div style="position:absolute;bottom: 14%;left: 0;right: 0;">
        <aside class="pl-6">
            <h1 class="is-size-7 is-uppercase has-text-grey-light mt-3">
                Received By
                @if (!$siv->received_by)
                    : <div
                        class="is-inline-block"
                        style="border: 1px solid lightgrey;width: 18%"
                    ></div>
                @endif
            </h1>
            @if ($siv->received_by)
                <h1 class="has-text-weight-bold has-text-grey-dark is-capitalized">
                    {{ $siv->received_by }}
                </h1>
            @endif
            <h1 class="is-uppercase has-text-grey-light has-text-weight-dark is-size-7 mb-4 mt-3">
                <div>
                    Signature
                </div>
                <div
                    class="mt-6"
                    style="border: 1px solid lightgrey;width: 30%"
                ></div>
            </h1>
        </aside>
    </div>
    <div style="position:absolute;bottom: 14%;left: 15%;right: 0;margin-left: 40%">
        <aside class="pl-6">
            <h1 class="is-size-7 is-uppercase has-text-grey-light mt-3">
                Delivered By
                @if (!$siv->delivered_by)
                    : <div
                        class="is-inline-block"
                        style="border: 1px solid lightgrey;width: 38%"
                    ></div>
                @endif
            </h1>
            @if ($siv->delivered_by)
                <h1 class="has-text-weight-bold has-text-grey-dark is-capitalized">
                    {{ $siv->delivered_by }}
                </h1>
            @endif
            <h1 class="is-uppercase has-text-grey-light has-text-weight-dark is-size-7 mb-4 mt-3">
                <div>
                    Signature
                </div>
                <div
                    class="mt-6"
                    style="border: 1px solid lightgrey;width: 70%"
                ></div>
            </h1>
        </aside>
    </div>
    @if ($siv->createdBy->is($siv->approvedBy))
        <footer style="position:absolute;bottom: 0%;left: 0;right: 0;">
            <aside class="pl-6">
                <h1 class="is-size-7 is-uppercase has-text-grey-light mt-3">
                    Prepared & Approved By
                </h1>
                <h1 class="has-text-weight-bold has-text-grey-dark is-capitalized">
                    {{ $siv->createdBy->name }}
                </h1>
                <h1 class="is-uppercase has-text-grey-light has-text-weight-dark is-size-7 mb-4 mt-5">
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
                    {{ $siv->createdBy->name }}
                </h1>
                <h1 class="is-uppercase has-text-grey-light has-text-weight-dark is-size-7 mb-4 mt-5">
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
                    {{ $siv->approvedBy->name }}
                </h1>
                <h1 class="is-uppercase has-text-grey-light has-text-weight-dark is-size-7 mb-4 mt-5">
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
