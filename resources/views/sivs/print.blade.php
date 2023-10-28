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

    @include('assets.print-css')
</head>

<body class="{{ userCompany()->hasPrintTemplate() ? 'company-background company-y-padding company-x-padding' : 'px-6' }}">
    @if (!userCompany()->hasPrintTemplate())
        <x-print.header :warehouse="$siv->warehouse" />
    @endif

    <hr
        class="my-0 has-background-grey-lighter"
        style="margin-left: -10%;margin-right: -10%"
    >

    <section class="is-clearfix py-3">
        <aside
            class="is-pulled-left"
            style="width: 25% !important"
        >
            <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
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
            <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
                Issued On
            </h1>
            <h1 class="has-text-black is-size-6 pr-2">
                {{ $siv->issued_on->toFormattedDateString() }}
            </h1>
        </aside>
        @if ($siv->isAssociated())
            <aside
                class="is-pulled-left"
                style="width: 25% !important"
            >
                <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
                    Purpose
                </h1>
                <h1 class="has-text-black is-size-6 pr-2">
                    @if (str($siv->sivable_type)->endsWith('Gdn'))
                        DO
                    @elseif(str($siv->sivable_type)->endsWith('Transfer'))
                        Transfer
                    @elseif(str($siv->sivable_type)->endsWith('Sale'))
                        Invoice
                    @endif
                    {{ ' No: ' . $siv->sivable->code }}
                </h1>
            </aside>
        @endif
        @if ($siv->issued_to)
            <aside
                class="is-pulled-left"
                style="width: 25% !important"
            >
                <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
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
                    <th>From</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($siv->sivDetails as $sivDetail)
                    <tr>
                        <td> {{ $loop->index + 1 }} </td>
                        <td> {{ $sivDetail->product->name }} </td>
                        @if (userCompany()->showProductCodeOnPrintouts())
                            <td> {{ $sivDetail->product->code ?? '-' }} </td>
                        @endif
                        <td> {{ number_format($sivDetail->quantity, 2) }} </td>
                        <td> {{ $sivDetail->product->unit_of_measurement }} </td>
                        <td> {{ $sivDetail->warehouse->name }} </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>

    <footer class="is-clearfix my-6">
        <aside
            class="is-pulled-left"
            style="width: 50%"
        >
            <h1 class="is-size-7 is-uppercase has-text-black-lighter has-text-weight-bold">
                Received By
                @if (is_null($siv->received_by))
                    :
                    <span
                        class="is-inline-block"
                        style="border: 1px solid lightgrey;width: 50%"
                    ></span>
                @endif
            </h1>
            @if ($siv->received_by)
                <h1 class="has-text-weight-bold has-text-grey-dark is-capitalized">
                    {{ $siv->received_by }}
                </h1>
            @endif
        </aside>
        <aside
            class="is-pulled-right"
            style="width: 50%"
        >
            <h1 class="is-size-7 is-uppercase has-text-black-lighter has-text-weight-bold">
                Delivered By
                @if (is_null($siv->delivered_by))
                    :
                    <span
                        class="is-inline-block"
                        style="border: 1px solid lightgrey;width: 50%"
                    ></span>
                @endif
            </h1>
            @if ($siv->delivered_by)
                <h1 class="has-text-weight-bold has-text-grey-dark is-capitalized">
                    {{ $siv->delivered_by }}
                </h1>
            @endif
        </aside>
    </footer>

    <x-print.user
        :created-by="$siv->createdBy ?? null"
        :approved-by="$siv->approvedBy ?? null"
    />

    <x-print.footer-marketing />
</body>

</html>
