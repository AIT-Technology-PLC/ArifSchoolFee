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
    <x-print.header :warehouse="$transaction->warehouse" />

    <main>
        <x-print.customer :customer="$transaction->customer ?? ''" />

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
                    {{ $transaction->code }}
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
                    {{ $transaction->issued_on->toFormattedDateString() }}
                </h1>
            </aside>
            @if ($transaction->payment_type)
                <aside
                    class="is-pulled-left"
                    style="width: 25% !important"
                >
                    <h1 class="is-uppercase has-text-grey-light has-text-weight-bold is-size-7">
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

        <section class="px-6 table-breaked">
            <table class="table is-bordered is-hoverable is-fullwidth is-narrow is-size-7">
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
                                <td>
                                    {{ $detail[$column] }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    @if ($transaction->pad->hasPrices())
                        <tr>
                            <td
                                colspan="{{ userCompany()->isDiscountBeforeVAT() ? count($columns) - 1 : count($columns) }}"
                                class="is-borderless"
                            ></td>
                            <td class="has-text-weight-bold">Sub-Total</td>
                            <td class="has-text-right">{{ number_format($transaction->subtotalPrice, 2) }}</td>
                        </tr>
                        <tr>
                            <td
                                colspan="{{ userCompany()->isDiscountBeforeVAT() ? count($columns) - 1 : count($columns) }}"
                                class="is-borderless"
                            ></td>
                            <td class="has-text-weight-bold">VAT 15%</td>
                            <td class="has-text-right">{{ number_format($transaction->vat, 2) }}</td>
                        </tr>
                        <tr>
                            <td
                                colspan="{{ userCompany()->isDiscountBeforeVAT() ? count($columns) - 1 : count($columns) }}"
                                class="is-borderless"
                            ></td>
                            <td class="has-text-weight-bold">Grand Total</td>
                            <td class="has-text-right has-text-weight-bold">{{ number_format($transaction->grandTotalPrice, 2) }}</td>
                        </tr>
                        @if (!userCompany()->isDiscountBeforeVAT())
                            <tr>
                                <td
                                    colspan="{{ userCompany()->isDiscountBeforeVAT() ? count($columns) - 1 : count($columns) }}"
                                    class="is-borderless"
                                ></td>
                                <td class="has-text-weight-bold">Discount</td>
                                <td class="has-text-right has-text-weight-bold">{{ number_format($transaction->discount * 100, 2) }}%</td>
                            </tr>
                            <tr>
                                <td
                                    colspan="{{ userCompany()->isDiscountBeforeVAT() ? count($columns) - 1 : count($columns) }}"
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
    </main>

    <div style="margin-bottom: 27% !important">&nbsp;</div>

    @if ($transaction->pad->isInventoryOperationSubtract() || $transaction->customer)
        <footer style="position:absolute;bottom: 14%;left: 0;right: 0;">
            <aside class="pl-6 my-5">
                <h1 class="title is-size-7 is-uppercase mb-6">
                    I received the above goods/services in good condition
                    <br>
                </h1>
                <div style="border: 1px solid lightgrey;width: 50%"></div>
            </aside>
        </footer>
    @endif

    @if ($transaction->createdBy->is($transaction->approvedBy))
        <footer style="position:absolute;bottom: 0%;left: 0;right: 0;">
            <aside class="pl-6">
                <h1 class="is-size-7 is-uppercase has-text-grey-light mt-3">
                    Prepared & Approved By
                </h1>
                <h1 class="has-text-weight-bold has-text-grey-dark is-capitalized">
                    {{ $transaction->createdBy->name }}
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
                    {{ $transaction->createdBy->name }}
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
        @if ($transaction->pad->isApprovable())
            <footer style="position:absolute;bottom: 0%;left: 15%;right: 0;margin-left: 40%">
                <aside class="pl-6">
                    <h1 class="is-size-7 is-uppercase has-text-grey-light mt-3">
                        Approved By
                    </h1>
                    <h1 class="has-text-weight-bold has-text-grey-dark is-capitalized">
                        {{ $transaction->approvedBy?->name }}
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
    @endif
</body>

</html>
