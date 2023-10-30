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
    <title> PI #{{ $proformaInvoice->reference }} - {{ userCompany()->name }} </title>
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
        <x-print.header :warehouse="$proformaInvoice->warehouse" />
    @endif

    <hr
        class="my-0 has-background-grey-lighter"
        style="margin-left: -10%;margin-right: -10%"
    >

    <x-print.customer
        :customer="$proformaInvoice->customer ?? ''"
        :contact="$proformaInvoice->contact"
    />

    <section class="is-clearfix py-3">
        <aside
            class="is-pulled-left"
            style="width: 25% !important"
        >
            <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
                PI N<u>o</u>
            </h1>
            <h1 class="has-text-black is-size-6 pr-2">
                {{ $proformaInvoice->reference }}
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
                {{ $proformaInvoice->issued_on->toFormattedDateString() }}
            </h1>
        </aside>
        <aside
            class="is-pulled-left"
            style="width: 25% !important"
        >
            <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
                Expiry Date
            </h1>
            <h1 class="has-text-black is-size-6 pr-2">
                {{ $proformaInvoice->expires_on->toFormattedDateString() }}
            </h1>
        </aside>
        @foreach ($proformaInvoice->printableCustomFields(1) as $field)
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
        @endforeach
    </section>

    <section class="pt-5 has-text-centered">
        <h1 class="is-uppercase has-text-grey-dark has-text-weight-bold is-size-4 is-underlined">
            Proforma Invoice
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
                    @if ($havingBatch)
                        <th>Batch No</th>
                        <th>Expiry Date</th>
                    @endif
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Unit Price</th>
                    @if (userCompany()->isDiscountBeforeTax())
                        <th>Discount</th>
                    @endif
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($proformaInvoice->proformaInvoiceDetails as $proformaInvoiceDetail)
                    <tr>
                        <td class="has-text-centered"> {{ $loop->index + 1 }} </td>
                        <td>
                            {{ $proformaInvoiceDetail->product->name ?? $proformaInvoiceDetail->custom_product }}
                            <span class="summernote-table">
                                {!! $proformaInvoiceDetail->specification ?? '' !!}
                            </span>
                        </td>
                        @if (userCompany()->showProductCodeOnPrintouts())
                            <td> {{ $proformaInvoiceDetail->product->code ?? '-' }} </td>
                        @endif
                        @if ($havingBatch)
                            <td> {{ $proformaInvoiceDetail->merchandiseBatch?->batch_no ?? '-' }} </td>
                            <td> {{ $proformaInvoiceDetail->merchandiseBatch?->expires_on?->toFormattedDateString() ?? '-' }} </td>
                        @endif
                        <td class="has-text-right"> {{ number_format($proformaInvoiceDetail->quantity, 2) }} </td>
                        <td class="has-text-centered"> {{ $proformaInvoiceDetail->product->unit_of_measurement ?? 'Piece' }} </td>
                        <td class="has-text-right"> {{ number_format($proformaInvoiceDetail->unit_price, 2) }} </td>
                        @if (userCompany()->isDiscountBeforeTax())
                            <td class="has-text-right"> {{ number_format($proformaInvoiceDetail->discount, 2) }}% </td>
                        @endif
                        <td class="has-text-right"> {{ number_format($proformaInvoiceDetail->totalPrice, 2) }} </td>
                    </tr>
                @endforeach
                <tr>
                    <td
                        colspan="{{ 4 + (userCompany()->isDiscountBeforeTax() ? 1 : 0) + (userCompany()->showProductCodeOnPrintouts() ? 1 : 0) + ($havingBatch ? 2 : 0) }}"
                        class="is-borderless"
                    ></td>
                    <td class="has-text-weight-bold">Sub-Total</td>
                    <td class="has-text-right">{{ number_format($proformaInvoice->subtotalPrice, 2) }}</td>
                </tr>
                <tr>
                    <td
                        colspan="{{ 4 + (userCompany()->isDiscountBeforeTax() ? 1 : 0) + (userCompany()->showProductCodeOnPrintouts() ? 1 : 0) + ($havingBatch ? 2 : 0) }}"
                        class="is-borderless"
                    ></td>
                    <td class="has-text-weight-bold">Tax</td>
                    <td class="has-text-right">{{ number_format($proformaInvoice->tax, 2) }}</td>
                </tr>
                <tr>
                    <td
                        colspan="{{ 4 + (userCompany()->isDiscountBeforeTax() ? 1 : 0) + (userCompany()->showProductCodeOnPrintouts() ? 1 : 0) + ($havingBatch ? 2 : 0) }}"
                        class="is-borderless"
                    ></td>
                    <td class="has-text-weight-bold">Grand Total</td>
                    <td class="has-text-right has-text-weight-bold">{{ number_format($proformaInvoice->grandTotalPrice, 2) }}</td>
                </tr>
                @if (!userCompany()->isDiscountBeforeTax())
                    <tr>
                        <td
                            colspan="{{ 4 + (userCompany()->showProductCodeOnPrintouts() ? 1 : 0) + ($havingBatch ? 2 : 0) }}"
                            class="is-borderless"
                        ></td>
                        <td class="has-text-weight-bold">Discount</td>
                        <td class="has-text-right has-text-weight-bold">{{ number_format($proformaInvoice->discount, 2) }}%</td>
                    </tr>
                    <tr>
                        <td
                            colspan="{{ 4 + (userCompany()->showProductCodeOnPrintouts() ? 1 : 0) + ($havingBatch ? 2 : 0) }}"
                            class="is-borderless"
                        ></td>
                        <td class="has-text-weight-bold">
                            Grand Total
                            <br>
                            <span class="has-text-grey">
                                After Discount
                            </span>
                        </td>
                        <td class="has-text-right has-text-weight-bold">{{ number_format($proformaInvoice->grandTotalPriceAfterDiscount, 2) }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </section>

    <section
        class="page-break my-6"
        style="width: 60% !important"
    >
        @if ($proformaInvoice->terms)
            <aside>
                <h1 class="has-text-weight-bold has-text-grey-dark is-size-6 is-capitalized">
                    Terms and Conditions
                </h1>
                <div class="is-size-7 summernote-table">
                    {!! $proformaInvoice->terms !!}
                </div>
            </aside>
        @endif
    </section>

    <x-print.user
        :created-by="$proformaInvoice->createdBy ?? null"
        :approved-by="null"
    />

    <x-print.footer-marketing />
</body>

</html>
