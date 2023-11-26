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
    <title> Reservation #{{ $reservation->code }} - {{ userCompany()->name }} </title>
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
        <x-print.header :warehouse="$reservation->warehouse" />
    @endif

    <hr
        class="my-0 has-background-grey-lighter"
        style="margin-left: -10%;margin-right: -10%"
    >

    <x-print.customer
        :customer="$reservation->customer ?? ''"
        :contact="$reservation->contact"
    />

    <section class="is-clearfix py-3">
        <aside
            class="is-pulled-left"
            style="width: 25% !important"
        >
            <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
                N<u>o</u>
            </h1>
            <h1 class="has-text-black is-size-6 pr-2">
                {{ $reservation->code }}
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
                {{ $reservation->issued_on->toFormattedDateString() }}
            </h1>
        </aside>
        @foreach ($reservation->printableCustomFields(2) as $field)
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

    <hr
        class="my-0 has-background-grey-lighter"
        style="margin-left: -10%;margin-right: -10%"
    >

    <x-print.payment :model="$reservation" />

    <section class="pt-5 has-text-centered">
        <h1 class="is-uppercase has-text-grey-dark has-text-weight-bold is-size-4 is-underlined">
            Reservation Voucher
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
                    <th>Unit</th>
                    <th>Unit Price</th>
                    @if (userCompany()->isDiscountBeforeTax())
                        <th>Discount</th>
                    @endif
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reservation->reservationDetails as $reservationDetail)
                    <tr>
                        <td class="has-text-centered"> {{ $loop->index + 1 }} </td>
                        <td>
                            {{ $reservationDetail->product->name }}
                        </td>
                        @if (userCompany()->showProductCodeOnPrintouts())
                            <td> {{ $reservationDetail->product->code ?? '-' }} </td>
                        @endif
                        <td class="has-text-right"> {{ number_format($reservationDetail->quantity, 2) }} </td>
                        <td class="has-text-centered"> {{ $reservationDetail->product->unit_of_measurement }} </td>
                        <td class="has-text-right"> {{ number_format($reservationDetail->unit_price, 2) }} </td>
                        @if (userCompany()->isDiscountBeforeTax())
                            <td class="has-text-right"> {{ number_format($reservationDetail->discount, 2) }}% </td>
                        @endif
                        <td class="has-text-right"> {{ number_format($reservationDetail->totalPrice, 2) }} </td>
                    </tr>
                @endforeach
                <tr>
                    <td
                        colspan="{{ 4 + (userCompany()->isDiscountBeforeTax() ? 1 : 0) + (userCompany()->showProductCodeOnPrintouts() ? 1 : 0) }}"
                        class="is-borderless"
                    ></td>
                    <td class="has-text-weight-bold">Sub-Total</td>
                    <td class="has-text-right">{{ number_format($reservation->subtotalPrice, 2) }}</td>
                </tr>
                <tr>
                    <td
                        colspan="{{ 4 + (userCompany()->isDiscountBeforeTax() ? 1 : 0) + (userCompany()->showProductCodeOnPrintouts() ? 1 : 0) }}"
                        class="is-borderless"
                    ></td>
                    <td class="has-text-weight-bold">Tax</td>
                    <td class="has-text-right">{{ number_format($reservation->tax, 2) }}</td>
                </tr>
                @if ($reservation->hasWithholding())
                    <tr>
                        <td
                            colspan="{{ 4 + (userCompany()->showProductCodeOnPrintouts() ? 1 : 0) }}"
                            class="is-borderless"
                        ></td>
                        <td class="has-text-weight-bold">Withholding Tax ({{ userCompany()->withholdingTaxes['tax_rate'] * 100 }}%)</td>
                        <td class="has-text-right">{{ number_format($reservation->totalWithheldAmount, 2) }}</td>
                    </tr>
                @endif
                <tr>
                    <td
                        colspan="{{ 4 + (userCompany()->isDiscountBeforeTax() ? 1 : 0) + (userCompany()->showProductCodeOnPrintouts() ? 1 : 0) }}"
                        class="is-borderless"
                    ></td>
                    <td class="has-text-weight-bold">Grand Total</td>
                    <td class="has-text-right has-text-weight-bold">{{ number_format($reservation->grandTotalPrice, 2) }}</td>
                </tr>
                @if (!userCompany()->isDiscountBeforeTax())
                    <tr>
                        <td
                            colspan="{{ userCompany()->showProductCodeOnPrintouts() ? 5 : 4 }}"
                            class="is-borderless"
                        ></td>
                        <td class="has-text-weight-bold">Discount</td>
                        <td class="has-text-right has-text-weight-bold">{{ number_format($reservation->discount, 2) }}%</td>
                    </tr>
                    <tr>
                        <td
                            colspan="{{ userCompany()->showProductCodeOnPrintouts() ? 5 : 4 }}"
                            class="is-borderless"
                        ></td>
                        <td class="has-text-weight-bold">
                            Grand Total
                            <br>
                            <span class="has-text-grey">
                                After Discount
                            </span>
                        </td>
                        <td class="has-text-right has-text-weight-bold">{{ number_format($reservation->grandTotalPriceAfterDiscount, 2) }}</td>
                    </tr>
                @endif
                @if ($reservation->hasWithholding())
                    <tr>
                        <td
                            colspan="{{ 4 + (userCompany()->showProductCodeOnPrintouts() ? 1 : 0)    }}"
                            class="is-borderless"
                        ></td>
                        <td class="has-text-weight-bold"></td>
                        <td class="has-text-right has-text-weight-bold">{{ number_format($reservation->grandTotalPrice - $reservation->totalWithheldAmount, 2) }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </section>

    <section class="mt-6">
        <x-print.user
            :created-by="$reservation->createdBy ?? null"
            :approved-by="$reservation->approvedBy ?? null"
        />
    </section>

    <x-print.footer-marketing />
</body>

</html>
