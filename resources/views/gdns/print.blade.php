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

<body class="{{ userCompany()->hasPrintTemplate() ? 'company-background' : '' }}">
    @if (!userCompany()->hasPrintTemplate())
        <x-print.header :warehouse="$gdn->warehouse" />
    @endif

    <main @class(['company-y-padding' => userCompany()->hasPrintTemplate()])>
        <div @class([
            'company-x-padding' => userCompany()->hasPrintTemplate(),
            'px-6' => !userCompany()->hasPrintTemplate(),
        ])>
            <x-print.customer :customer="$gdn->customer ?? ''" />
        </div>

        <section @class([
            'is-clearfix py-3',
            'company-x-padding' => userCompany()->hasPrintTemplate(),
            'px-6' => !userCompany()->hasPrintTemplate(),
        ])>
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

        <section @class([
            'is-clearfix py-3',
            'company-x-padding' => userCompany()->hasPrintTemplate(),
            'px-6' => !userCompany()->hasPrintTemplate(),
        ])>
            <aside
                class="is-pulled-left"
                style="width: 25% !important"
            >
                <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
                    Payment Type
                </h1>
                <h1 class="has-text-black is-size-6 pr-2">
                    {{ $gdn->payment_type }}
                </h1>
            </aside>
            <aside
                class="is-pulled-left"
                style="width: 25% !important"
            >
                <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
                    Cash Amount
                </h1>
                <h1 class="has-text-black is-size-6 pr-2">
                    {{ number_format($gdn->payment_in_cash, 2) }}
                    ({{ number_format($gdn->cashReceivedInPercentage, 2) }}%)
                </h1>
            </aside>
            @if (!$gdn->isPaymentInCash())
                <aside
                    class="is-pulled-left"
                    style="width: 25% !important"
                >
                    <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
                        Credit Amount
                    </h1>
                    <h1 class="has-text-black is-size-6 pr-2">
                        {{ number_format($gdn->payment_in_credit, 2) }}
                        ({{ number_format($gdn->creditPayableInPercentage, 2) }}%)
                    </h1>
                </aside>
            @endif
        </section>

        <section @class([
            'pt-5 has-text-centered',
            'company-x-padding' => userCompany()->hasPrintTemplate(),
            'px-6' => !userCompany()->hasPrintTemplate(),
        ])>
            <h1 class="is-uppercase has-text-grey-dark has-text-weight-bold is-size-4 is-underlined">
                Delivery Note
            </h1>
        </section>

        <section @class([
            'table-breaked',
            'company-x-padding has-background-white' => userCompany()->hasPrintTemplate(),
            'px-6' => !userCompany()->hasPrintTemplate(),
        ])>
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
    </main>

    <div style="margin-bottom: {{ userCompany()->hasPrintTemplate() ? userCompany()->print_padding_bottom + 18 : '18' }}% !important">&nbsp;</div>

    <footer
        @class([
            'my-5',
            'company-x-padding' => userCompany()->hasPrintTemplate(),
            'pl-6' => !userCompany()->hasPrintTemplate(),
        ])
        style="position:absolute;bottom: {{ userCompany()->hasPrintTemplate() ? userCompany()->print_padding_bottom + 5 : '5' }}%;left: 0;right: 0;"
    >
        <h1 class="title is-size-7 is-uppercase mb-6">
            I received the above goods/services in good condition
            <br>
        </h1>
        <div style="border: 1px solid lightgrey;width: 50%"></div>
    </footer>
    @if ($gdn->createdBy->is($gdn->approvedBy))
        <footer
            @class([
                'company-x-padding' => userCompany()->hasPrintTemplate(),
                'pl-6' => !userCompany()->hasPrintTemplate(),
            ])
            style="position:absolute;bottom: {{ userCompany()->hasPrintTemplate() ? userCompany()->print_padding_bottom : '0' }}%;left: 0;right: 0;"
        >
            <h1 class="is-size-7 is-uppercase has-text-black-lighter has-text-weight-bold mt-3">
                Prepared & Approved By
            </h1>
            <h1 class="has-text-weight-bold has-text-grey-dark is-capitalized">
                {{ $gdn->createdBy->name }}
            </h1>
        </footer>
    @else
        <footer
            @class([
                'company-x-padding' => userCompany()->hasPrintTemplate(),
                'pl-6' => !userCompany()->hasPrintTemplate(),
            ])
            style="position:absolute;bottom: {{ userCompany()->hasPrintTemplate() ? userCompany()->print_padding_bottom : '0' }}%;left: 0;right: 0;"
        >
            <h1 class="is-size-7 is-uppercase has-text-black-lighter has-text-weight-bold mt-3">
                Prepared By
            </h1>
            <h1 class="has-text-weight-bold has-text-grey-dark is-capitalized">
                {{ $gdn->createdBy->name }}
            </h1>
        </footer>
        <footer
            @class([
                'company-x-padding' => userCompany()->hasPrintTemplate(),
                'pl-6' => !userCompany()->hasPrintTemplate(),
            ])
            style="position:absolute;bottom: {{ userCompany()->hasPrintTemplate() ? userCompany()->print_padding_bottom : '0' }}%;left: 15%;right: 0;margin-left: 40%"
        >
            <h1 class="is-size-7 is-uppercase has-text-black-lighter has-text-weight-bold mt-3">
                Approved By
            </h1>
            <h1 class="has-text-weight-bold has-text-grey-dark is-capitalized">
                {{ $gdn->approvedBy->name }}
            </h1>
        </footer>
    @endif
</body>

</html>
