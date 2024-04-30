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
    <title> Tender "{{ $tender->code }}" - {{ userCompany()->name }} </title>
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
        <x-print.header :warehouse="$tender->warehouse" />
    @endif

    <hr
        class="my-0 has-background-grey-lighter"
        style="margin-left: -10%;margin-right: -10%"
    >

    <section class="pt-5 has-text-centered">
        <h1 class="is-uppercase has-text-grey-dark has-text-weight-bold is-size-4 is-underlined">
            Tender Checklist
        </h1>
    </section>

    <section class="table-breaked">
        <table class="table is-bordered is-hoverable is-fullwidth is-narrow is-size-7 is-transparent-color">
            <thead>
                <tr class="is-borderless">
                    <td
                        colspan="3"
                        class="is-borderless"
                    >&nbsp;</td>
                </tr>
                <tr class="is-borderless">
                    <td
                        colspan="3"
                        class="is-borderless"
                    >&nbsp;</td>
                </tr>
                <tr>
                    <th>#</th>
                    <th>Description</th>
                    <th>Comment</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tender->tenderChecklists as $tenderChecklist)
                    <tr>
                        <td class="has-text-centered"> {{ $loop->index + 1 }} </td>
                        <td> {{ $tenderChecklist->generalTenderChecklist->item }} </td>
                        <td> {{ $tenderChecklist->comment }} </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>

    <footer class="mt-6">
        <h1 class="is-size-7 is-uppercase has-text-black-lighter has-text-weight-bold">
            Prepared & Approved By
        </h1>
        <div
            class="mt-3"
            style="border-bottom: 1px solid lightgrey;width: 20%;"
        >&nbsp;</div>
    </footer>

    <x-print.footer-marketing />
</body>

</html>
