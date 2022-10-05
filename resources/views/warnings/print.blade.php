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
    <title> Warning Letter #{{ $warning->code }} - {{ userCompany()->name }} </title>
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
        <x-print.header :warehouse="$warning->warehouse" />
    @endif

    <hr
        class="my-0 has-background-grey-lighter"
        style="margin-left: -10%;margin-right: -10%"
    >

    <section class="is-clearfix pt-3">
        <aside
            class="is-pulled-left"
            style="width: 33% !important"
        >
            <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-size-7">
                Date: {{ $warning->issued_on->toFormattedDateString() }}
            </h1>
        </aside>
        <aside
            class="is-pulled-left"
            style="width: 33% !important"
        >
            <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-size-7">
                Employee Name: {{ $warning->employee->user->name }}
            </h1>
        </aside>
        <aside
            class="is-pulled-left"
            style="width: 33% !important"
        >
            <h1 class="is-uppercase has-text-black-lighter has-text-weight-bold is-size-7">
                Type: {{ $warning->type }}
            </h1>
        </aside>
    </section>

    <section class="pt-5 has-text-centered">
        <h1 class="is-uppercase has-text-grey-dark has-text-weight-bold is-size-4 is-underlined">
            Letter of Warning
        </h1>
    </section>

    <section class="is-clearfix pt-3 pb-6">
        <div>
            {!! $warning->letter !!}
        </div>
    </section>

    <x-print.user
        :created-by="$warning->createdBy ?? null"
        :approved-by="$warning->createdBy ?? null"
    />

    <div style="position:absolute;bottom: 10%;left: 15%;right: 0;margin-left: 40%">
        <h1 class="is-uppercase has-text-grey-light has-text-weight-dark is-size-7 mb-4 mt-3">
            <div>
                Signature
            </div>
            <div
                class="mt-6"
                style="border: 1px solid lightgrey;width: 70%"
            ></div>
        </h1>
    </div>
</body>

</html>
