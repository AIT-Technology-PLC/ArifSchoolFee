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

    @include('assets.print-css', ['company' => userCompany()])
</head>

<body class="{{ userCompany()->hasPrintTemplate() ? 'company-background company-y-padding company-x-padding' : 'px-6' }}">
    @if (!userCompany()->hasPrintTemplate())
        <x-print.header :warehouse="$return->warehouse" />
    @endif

    <hr
        class="my-0 has-background-grey-lighter"
        style="margin-left: -10%;margin-right: -10%"
    >

    <x-print.customer :customer="$return->gdn->customer ?? ($return->customer ?? '')" />

    <section class="is-clearfix py-3">
        <aside
            class="is-pulled-left"
            style="width: 25% !important"
        >
            <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
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
            <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
                DO No
            </h1>
            <h1 class="has-text-black is-size-6 pr-2">
                {{ $return->gdn->code ?? 'N/A' }}
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
                {{ $return->issued_on->toFormattedDateString() }}
            </h1>
        </aside>
        @foreach ($return->printableCustomFields(1) as $field)
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
            Return Voucher
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
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($return->returnDetails as $returnDetail)
                    <tr>
                        <td class="has-text-centered"> {{ $loop->index + 1 }} </td>
                        <td> {{ $returnDetail->product->name }} </td>
                        @if (userCompany()->showProductCodeOnPrintouts())
                            <td> {{ $returnDetail->product->code ?? '-' }} </td>
                        @endif
                        <td class="has-text-right"> {{ number_format($returnDetail->quantity, 2) }} </td>
                        <td class="has-text-centered"> {{ $returnDetail->product->unit_of_measurement }} </td>
                        <td class="has-text-right"> {{ number_format($returnDetail->unit_price, 2) }} </td>
                        <td class="has-text-right"> {{ number_format($returnDetail->totalPrice, 2) }} </td>
                    </tr>
                @endforeach
                <tr>
                    <td
                        colspan="{{ userCompany()->showProductCodeOnPrintouts() ? 5 : 4 }}"
                        class="is-borderless"
                    ></td>
                    <td class="has-text-weight-bold">Sub-Total</td>
                    <td class="has-text-right">{{ number_format($return->subtotalPrice, 2) }}</td>
                </tr>
                <tr>
                    <td
                        colspan="{{ userCompany()->showProductCodeOnPrintouts() ? 5 : 4 }}"
                        class="is-borderless"
                    ></td>
                    <td class="has-text-weight-bold">Tax</td>
                    <td class="has-text-right">{{ number_format($return->tax, 2) }}</td>
                </tr>
                <tr>
                    <td
                        colspan="{{ userCompany()->showProductCodeOnPrintouts() ? 5 : 4 }}"
                        class="is-borderless"
                    ></td>
                    <td class="has-text-weight-bold">Grand Total</td>
                    <td class="has-text-right has-text-weight-bold">{{ number_format($return->grandTotalPrice, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </section>

    <section class="mt-6">
        <x-print.user
            :created-by="$return->createdBy ?? null"
            :approved-by="$return->approvedBy ?? null"
        />
    </section>

    <x-print.footer-marketing />
</body>

</html>
