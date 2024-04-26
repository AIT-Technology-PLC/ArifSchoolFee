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

    @include('assets.print-css')
</head>

<body class="{{ userCompany()->hasPrintTemplate() ? 'company-background company-y-padding company-x-padding' : 'px-6' }}">
    @if (!userCompany()->hasPrintTemplate())
        <x-print.header :warehouse="$transaction->warehouse" />
    @endif

    <hr
        class="my-0 has-background-grey-lighter"
        style="margin-left: -10%;margin-right: -10%"
    >

    <x-print.customer :customer="$transaction->customer ?? ''" />

    <section class="is-clearfix py-3">
        <aside
            class="is-pulled-left"
            style="width: 25% !important"
        >
            <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
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
            <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-underlined is-size-7">
                Issued On
            </h1>
            <h1 class="has-text-black is-size-6 pr-2">
                {{ $transaction->issued_on->toFormattedDateString() }}
            </h1>
        </aside>
    </section>

    <section class="pt-5 has-text-centered">
        <h1 class="is-uppercase has-text-grey-dark has-text-weight-bold is-size-4 is-underlined">
            {{ $transaction->pad->name }}
        </h1>
    </section>

    @if (!empty($columns['master']))
        <section class="table-breaked mt-5">
            <table class="table is-borderless is-fullwidth is-narrow is-size-7 is-transparent-color">
                <thead>
                    <tr>
                        @foreach ($columns['master'] as $column)
                            @continue($column == 'description')

                            <th>{{ str($column)->replace('_', ' ')->title() }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($columns['master'] as $column)
                        @continue($column == 'description')

                        <td class="has-text-centered">
                            {{ $transaction->transactionMasters->toArray()[$column] ?? '-' }}
                        </td>
                    @endforeach
                </tbody>
            </table>
        </section>
    @endif

    @if ($transaction->transactionDetails->isNotEmpty())
        <section class="table-breaked">
            <table class="table is-bordered is-hoverable is-fullwidth is-narrow is-size-7 is-transparent-color">
                <thead>
                    <tr class="is-borderless">
                        <td
                            colspan="{{ count($columns['detail']) }}"
                            class="is-borderless"
                        >&nbsp;</td>
                    </tr>
                    <tr class="is-borderless">
                        <td
                            colspan="{{ count($columns['detail']) }}"
                            class="is-borderless"
                        >&nbsp;</td>
                    </tr>
                    <tr>
                        <th>#</th>
                        @foreach ($columns['detail'] as $column)
                            @continue ($column == 'unit')

                            <th>{{ str($column)->replace('_', ' ')->title() }}</th>

                            @if (str($column)->replace('_', ' ')->title()->is('Product') && in_array('unit', $columns['detail']))
                                <th> Unit </th>
                            @endif
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaction->transactionDetails as $detail)
                        <tr>
                            <th>
                                {{ $loop->iteration }}
                            </th>
                            @foreach ($columns['detail'] as $column)
                                @continue ($column == 'unit')

                                <td>
                                    {{ $detail[$column] ?? '-' }}
                                </td>

                                @if (str($column)->replace('_', ' ')->title()->is('Product') && in_array('unit', $columns['detail']))
                                    <td>
                                        {{ $detail['unit'] ?? '-' }}
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                    @if ($transaction->pad->hasPrices())
                        <tr>
                            <td
                                colspan="{{ count($columns['detail']) - 1 }}"
                                class="is-borderless"
                            ></td>
                            <td class="has-text-weight-bold">Sub-Total</td>
                            <td class="has-text-right">{{ number_format($transaction->subtotalPrice, 2) }}</td>
                        </tr>
                        <tr>
                            <td
                                colspan="{{ count($columns['detail']) - 1 }}"
                                class="is-borderless"
                            ></td>
                            <td class="has-text-weight-bold">Tax</td>
                            <td class="has-text-right">{{ number_format($transaction->tax, 2) }}</td>
                        </tr>
                        <tr>
                            <td
                                colspan="{{ count($columns['detail']) - 1 }}"
                                class="is-borderless"
                            ></td>
                            <td class="has-text-weight-bold">Grand Total</td>
                            <td class="has-text-right has-text-weight-bold">{{ number_format($transaction->grandTotalPrice, 2) }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </section>
    @endif

    <footer class="my-6">
        @if ($transaction->pad->isInventoryOperationSubtract() || $transaction->customer)
            <h1 class="title is-size-7 is-uppercase">
                I received the above goods/services in good condition:
                <span
                    class="is-inline-block"
                    style="border: 1px solid lightgrey;width: 20%"
                ></span>
            </h1>
        @endif
    </footer>

    @if (isset($transaction->transactionMasters->toArray()['description']) &&
            str($transaction->transactionMasters->toArray()['description'])->stripTags()->squish()->length())
        <section
            class="page-break my-6"
            style="width: 60% !important"
        >
            <aside>
                <h1 class="has-text-weight-bold has-text-grey-dark is-size-6 is-capitalized is-underlined">
                    Description
                </h1>
                <div class="is-size-7 summernote-table mt-3">
                    {!! $transaction->transactionMasters->toArray()['description'] !!}
                </div>
            </aside>
        </section>
    @endif

    <x-print.user
        :created-by="$transaction->createdBy ?? null"
        :approved-by="$transaction->approvedBy ?? null"
    />

    <x-print.footer-marketing />
</body>

</html>
