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
    <header class="is-clearfix py-5 has-background-white-ter">
        <aside class="is-pulled-left ml-6 mt-5 pt-4">
            <img
                src="{{ asset('storage/' . userCompany()->logo) }}"
                style="width: 300px !important; height: 130px !important"
            >
        </aside>
        <aside class="is-pulled-right mr-6">
            <h1 class="heading is-capitalized has-text-black has-text-weight-medium is-size-5">
                {{ userCompany()->name }}
            </h1>
            <h1 class="is-uppercase has-text-grey-light has-text-weight-dark is-size-7 mb-0">
                Tel/Phone
            </h1>
            <p class="has-text-grey-dark has-text-weight-medium is-size-6">
                {{ userCompany()->phone ?? '-' }}
            </p>
            <h1 class="is-uppercase has-text-grey-light has-text-weight-dark is-size-7">
                Email
            </h1>
            <p class="has-text-grey-dark has-text-weight-medium is-size-6">
                {{ userCompany()->email ?? '-' }}
            </p>
            <h1 class="is-uppercase has-text-grey-light has-text-weight-dark is-size-7">
                Address
            </h1>
            <p class="has-text-grey-dark has-text-weight-medium is-size-6">
                {{ userCompany()->address ?? '-' }}
            </p>
        </aside>
    </header>

    <main>
        @if (userCompany()->canShowBranchDetailOnPrint())
            <section class="is-clearfix has-background-white-bis py-3 pl-6 pr-6">
                <aside class="is-pulled-left mr-6 pt-3">
                    <div>
                        <h1 class="is-uppercase has-text-grey-light has-text-weight-dark is-size-7">
                            Branch Name
                        </h1>
                        <p class="has-text-grey-dark has-text-weight-medium is-size-6">
                            {{ $proformaInvoice->warehouse->name }}
                        </p>
                    </div>
                </aside>
                <aside class="is-pulled-left mr-6 pt-3">
                    <div>
                        <h1 class="is-uppercase has-text-grey-light has-text-weight-dark is-size-7">
                            Location
                        </h1>
                        <p class="has-text-grey-dark has-text-weight-medium is-size-6">
                            {{ $proformaInvoice->warehouse->location }}
                        </p>
                    </div>
                </aside>
                @if ($proformaInvoice->warehouse->phone)
                    <aside class="is-pulled-left mr-6 pt-3">
                        <div>
                            <h1 class="is-uppercase has-text-grey-light has-text-weight-dark is-size-7">
                                Tel/Phone
                            </h1>
                            <p class="has-text-grey-dark has-text-weight-medium is-size-6">
                                {{ $proformaInvoice->warehouse->phone ?? '-' }}
                            </p>
                        </div>
                    </aside>
                @endif
                @if ($proformaInvoice->warehouse->email)
                    <aside class="is-pulled-left pt-3">
                        <div>
                            <h1 class="is-uppercase has-text-grey-light has-text-weight-dark is-size-7">
                                Email
                            </h1>
                            <p class="has-text-grey-dark has-text-weight-medium is-size-6">
                                {{ $proformaInvoice->warehouse->email ?? '-' }}
                            </p>
                        </div>
                    </aside>
                @endif
            </section>
        @endif
        @if ($proformaInvoice->customer)
            <section class="pb-3 has-background-white-bis">
                <aside class="ml-6">
                    <h1 class="is-uppercase has-text-grey-light has-text-weight-dark is-size-7">
                        Customer
                    </h1>
                    <h1 class="has-text-weight-bold has-text-grey-dark is-size-6 is-capitalized">
                        {{ $proformaInvoice->customer->company_name ?? '-' }}
                    </h1>
                    @if ($proformaInvoice->customer->tin)
                        <h1 class="has-text-weight-normal has-text-grey-dark is-size-6 is-capitalized">
                            {{ $proformaInvoice->customer->tin ?? '-' }}
                        </h1>
                    @endif
                    @if ($proformaInvoice->customer->address)
                        <h1 class="has-text-weight-normal has-text-grey-dark is-size-6 is-capitalized">
                            {{ $proformaInvoice->customer->address ?? '-' }}
                        </h1>
                    @endif
                </aside>
            </section>
        @endif
        <section class="is-clearfix py-5 has-background-white-bis">
            @if ($proformaInvoice->customer)
                <aside class="is-pulled-left ml-6">
                    <h1 class="is-uppercase has-text-grey-light has-text-weight-dark is-size-7">
                        Customer
                    </h1>
                    <h1 class="has-text-weight-bold has-text-grey-dark is-size-6 is-capitalized">
                        {{ $proformaInvoice->customer->company_name }}
                    </h1>
                    @if ($proformaInvoice->customer->contact_name)
                        <h1 class="is-uppercase has-text-grey-light has-text-weight-dark is-size-7">
                            Contact
                        </h1>
                        <h1 class="has-text-weight-bold has-text-grey-dark is-size-6 is-capitalized">
                            {{ $proformaInvoice->customer->contact_name }}
                        </h1>
                    @endif
                    @if ($proformaInvoice->customer->tin)
                        <h1 class="is-uppercase has-text-grey-light has-text-weight-dark is-size-7">
                            TIN
                        </h1>
                        <h1 class="has-text-weight-bold has-text-grey-dark is-size-6 is-capitalized">
                            {{ $proformaInvoice->customer->tin }}
                        </h1>
                    @endif
                    @if ($proformaInvoice->customer->address)
                        <h1 class="is-uppercase has-text-grey-light has-text-weight-dark is-size-7">
                            Address
                        </h1>
                        <h1 class="has-text-weight-bold has-text-grey-dark is-size-6 is-capitalized">
                            {{ $proformaInvoice->customer->address }}
                        </h1>
                    @endif
                </aside>
            @endif
            <aside class="is-pulled-right mr-6">
                <h1 class="is-uppercase has-text-grey-light has-text-weight-dark is-size-7">
                    PI N<u>o</u>
                </h1>
                <h1 class="has-text-weight-bold has-text-grey-dark is-size-6 is-capitalized">
                    {{ $proformaInvoice->reference }}
                </h1>
                <h1 class="is-uppercase has-text-grey-light has-text-weight-dark is-size-7">
                    Date
                </h1>
                <h1 class="has-text-weight-bold has-text-grey-dark is-size-6 is-capitalized">
                    {{ $proformaInvoice->issued_on->toFormattedDateString() }}
                </h1>
                @if ($proformaInvoice->expires_on)
                    <h1 class="is-uppercase has-text-grey-light has-text-weight-dark is-size-7">
                        Expiry Date
                    </h1>
                    <h1 class="has-text-weight-bold has-text-grey-dark is-size-6 is-capitalized">
                        {{ $proformaInvoice->expires_on->toFormattedDateString() }}
                    </h1>
                @endif
            </aside>
        </section>

        <section class="pt-5 has-text-centered">
            <h1 class="is-uppercase has-text-grey-dark has-text-weight-bold is-size-4 is-underlined">
                Proforma Invoice
            </h1>
        </section>

        <section class="px-6 table-breaked">
            <table class="table is-bordered is-hoverable is-fullwidth is-narrow is-size-7">
                <thead>
                    <tr class="is-borderless">
                        <td
                            colspan="{{ userCompany()->isDiscountBeforeVAT() ? 8 : 7 }}"
                            class="is-borderless"
                        >&nbsp;</td>
                    </tr>
                    <tr class="is-borderless">
                        <td
                            colspan="{{ userCompany()->isDiscountBeforeVAT() ? 8 : 7 }}"
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
                    @foreach ($proformaInvoice->proformaInvoiceDetails as $proformaInvoiceDetail)
                        <tr>
                            <td class="has-text-centered"> {{ $loop->index + 1 }} </td>
                            <td>
                                {{ $proformaInvoiceDetail->product->name ?? $proformaInvoiceDetail->custom_product }}
                                <span class="summernote-table">
                                    {!! $proformaInvoiceDetail->specification ?? '' !!}
                                </span>
                            </td>
                            <td> {{ $proformaInvoiceDetail->product->code ?? '-' }} </td>
                            <td class="has-text-right"> {{ number_format($proformaInvoiceDetail->quantity, 2) }} </td>
                            <td class="has-text-centered"> {{ $proformaInvoiceDetail->product->unit_of_measurement }} </td>
                            <td class="has-text-right"> {{ number_format($proformaInvoiceDetail->unit_price, 2) }} </td>
                            @if (userCompany()->isDiscountBeforeVAT())
                                <td class="has-text-right"> {{ number_format($proformaInvoiceDetail->discount * 100, 2) }}% </td>
                            @endif
                            <td class="has-text-right"> {{ number_format($proformaInvoiceDetail->totalPrice, 2) }} </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td
                            colspan="{{ userCompany()->isDiscountBeforeVAT() ? 6 : 5 }}"
                            class="is-borderless"
                        ></td>
                        <td class="has-text-weight-bold">Sub-Total</td>
                        <td class="has-text-right">{{ number_format($proformaInvoice->subtotalPrice, 2) }}</td>
                    </tr>
                    <tr>
                        <td
                            colspan="{{ userCompany()->isDiscountBeforeVAT() ? 6 : 5 }}"
                            class="is-borderless"
                        ></td>
                        <td class="has-text-weight-bold">VAT 15%</td>
                        <td class="has-text-right">{{ number_format($proformaInvoice->vat, 2) }}</td>
                    </tr>
                    <tr>
                        <td
                            colspan="{{ userCompany()->isDiscountBeforeVAT() ? 6 : 5 }}"
                            class="is-borderless"
                        ></td>
                        <td class="has-text-weight-bold">Grand Total</td>
                        <td class="has-text-right has-text-weight-bold">{{ number_format($proformaInvoice->grandTotalPrice, 2) }}</td>
                    </tr>
                    @if (!userCompany()->isDiscountBeforeVAT())
                        <tr>
                            <td
                                colspan="5"
                                class="is-borderless"
                            ></td>
                            <td class="has-text-weight-bold">Discount</td>
                            <td class="has-text-right has-text-weight-bold">{{ number_format($proformaInvoice->discount * 100, 2) }}%</td>
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
                            <td class="has-text-right has-text-weight-bold">{{ number_format($proformaInvoice->grandTotalPriceAfterDiscount, 2) }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </section>
        @if ($proformaInvoice->terms)
            <section
                class="page-break mt-5 px-6"
                style="width: 60% !important"
            >
                <aside>
                    <h1 class="has-text-weight-bold has-text-grey-dark is-size-6 is-capitalized">
                        Terms and Conditions
                    </h1>
                    <div class="is-size-7 summernote-table">
                        {!! $proformaInvoice->terms !!}
                    </div>
                </aside>
            </section>
        @endif
    </main>
    <footer
        class="has-background-white-ter"
        style="position:absolute;bottom: 0%;left: 0;right: 0;margin-top: 132px"
    >
        <aside class="has-text-centered">
            <h1 class="is-size-7 is-uppercase has-text-grey-light mb-0 mt-3">
                Prepared By
            </h1>
            <h1 class="has-text-weight-bold has-text-grey-dark is-size-6 is-capitalized">
                {{ $proformaInvoice->createdBy->name }}
            </h1>
            <h1 class="is-size-7 is-uppercase has-text-grey-light mb-6 mt-3">
                Signature
            </h1>
        </aside>
    </footer>
</body>

</html>
