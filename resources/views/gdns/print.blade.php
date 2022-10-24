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

    @include('assets.print-css')
</head>

<body class="{{ userCompany()->hasPrintTemplate() ? 'company-background company-y-padding company-x-padding' : 'px-6' }}">
    @if (!userCompany()->hasPrintTemplate())
        <x-print.header :warehouse="$gdn->warehouse" />
    @endif

    <hr
        class="my-0 has-background-grey-lighter"
        style="margin-left: -10%;margin-right: -10%"
    >

    <x-print.customer
        :customer="$gdn->customer ?? ''"
        :contact="$gdn->contact"
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
                {{ $gdn->code }}
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
                {{ $gdn->issued_on->toFormattedDateString() }}
            </h1>
        </aside>
    </section>

    <hr
        class="my-0 has-background-grey-lighter"
        style="margin-left: -10%;margin-right: -10%"
    >

    <x-print.payment :model="$gdn" />

    <section class="pt-5 has-text-centered">
        <h1 class="is-uppercase has-text-grey-dark has-text-weight-bold is-size-4 is-underlined">
            Delivery Note
        </h1>
    </section>

    <section class="table-breaked">
        <table class="table is-bordered is-hoverable is-fullwidth is-narrow is-size-7 is-transparent-color">
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
        :created-by="$gdn->createdBy ?? null"
        :approved-by="$gdn->approvedBy ?? null"
    />
</body>

</html>
