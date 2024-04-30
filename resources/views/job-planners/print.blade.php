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
    <title> Job Planner #{{ userCompany()->name }} </title>
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
        <x-print.header :warehouse="authUser()->warehouse" />
    @endif

    <hr
        class="my-0 has-background-grey-lighter"
        style="margin-left: -10%;margin-right: -10%"
    >

    <section class="pt-5 has-text-centered">
        <h1 class="is-uppercase has-text-grey-dark has-text-weight-bold is-size-4 is-underlined">
            Production Plan
        </h1>
    </section>

    <section class="py-3">
        @foreach ($reportData as $row)
            <section>
                <h2 class="is-size-6"> Product: <strong>{{ $row->first()['product_name'] }}</strong></h2>
                <h2 class="is-size-6"> Bill Of Material: <strong>{{ $row->first()['bill_of_material'] }}</strong></h2>
                <h2 class="is-size-6"> Quantity: <strong>{{ number_format($row->first()['quantity'], 2) }} {{ $row->first()['product_unit_of_measurement'] }}</strong></h2>
                <h2 class="is-size-6"> Factory: <strong>{{ $row->first()['factory_name'] }}</strong></h2>
                <h2 class="is-size-6"> Production Capacity: <strong>{{ number_format($row->min('production_capacity'), 2) }} {{ $row->first()['product_unit_of_measurement'] }}</strong></h2>
            </section>
            <section class="table-breaked">
                <h3 class="pt-4 has-text-centered has-text-weight-bold is-uppercase is-size-7">
                    Production Capacity Report
                </h3>
                <table class="table is-bordered is-hoverable is-fullwidth is-narrow is-size-7 is-transparent-color">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Raw Material</th>
                            <th>Available Amount </th>
                            <th>Required Amount</th>
                            <th>Difference</th>
                            <th>Box</th>
                            <th>Production Capacity </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($row as $value)
                            <tr>
                                <td class="has-text-centered"> {{ $loop->index + 1 }} </td>
                                <td> {{ $value['raw_material'] }}</td>
                                <td class="has-text-right"> {{ number_format($value['available_amount'], 2) }}</td>
                                <td class="has-text-right"> {{ number_format($value['required_amount'], 2) }}</td>
                                <td class="{{ $value['difference'] >= 0 ? 'text-green' : 'text-purple' }} has-text-right"> {{ number_format($value['difference'], 2) }}</td>
                                <td> {{ $value['raw_material_unit_of_measurement'] }}</td>
                                <td class="has-text-right"> {{ number_format($value['production_capacity'], 2) }} {{ $value['product_unit_of_measurement'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
            @if (!$loop->last)
                <hr class="mt-6 mb-3">
            @endif
        @endforeach
    </section>

    <x-print.footer-marketing />
</body>

</html>
