<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> @yield('title') - {{ userCompany()->name }} </title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('img/favicon.png') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css" integrity="sha256-WLKGWSIJYerRN8tbNGtXWVYnUM5wMJTXD8eG4NtGcDM=" crossorigin="anonymous">
    {{-- Local Assets --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @include("pwa.tags")
</head>

<body>
    <header class="is-clearfix py-5 has-background-white-ter">
        <aside class="is-pulled-left ml-6 mt-6">
            <img src="{{ asset('storage/' . $proformaInvoice->company->logo) }}" width="30%">
        </aside>
        <aside class="is-pulled-right mr-6">
            <h1 class="heading is-capitalized has-text-black has-text-weight-medium is-size-5">
                {{ $proformaInvoice->company->name }}
            </h1>
            <h1 class="title is-uppercase has-text-grey-light has-text-weight-dark is-size-7">
                Tel/Phone
            </h1>
            <p class="title has-text-grey-dark has-text-weight-medium is-size-6">
                {{ $proformaInvoice->company->phone ?? '-' }}
            </p>
            <h1 class="title is-uppercase has-text-grey-light has-text-weight-dark is-size-7">
                Email
            </h1>
            <p class="title has-text-grey-dark has-text-weight-medium is-size-6">
                {{ $proformaInvoice->company->email ?? '-' }}
            </p>
            <h1 class="title is-uppercase has-text-grey-light has-text-weight-dark is-size-7">
                Address
            </h1>
            <p class="title has-text-grey-dark has-text-weight-medium is-size-6">
                {{ $proformaInvoice->company->address ?? '-' }}
            </p>
        </aside>
    </header>

    <main>
        Body Content
    </main>

    <footer>
        Footer Content
    </footer>

    @include('assets.js')
</body>

</html>
