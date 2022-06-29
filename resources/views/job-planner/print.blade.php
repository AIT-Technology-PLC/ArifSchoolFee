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
    <style>
        .page-break {
            page-break-inside: avoid;
        }

        @media print {
            .table-breaked {
                page-break-before: auto;
            }
        }

        .summernote-table table td,
        .summernote-table table th {
            border-width: 1px !important;
            padding: 0 !important;
        }

        td {
            padding-top: 0.25rem !important;
            padding-bottom: 0.25rem !important;
        }
    </style>
</head>

<body>
    <x-print.header :warehouse="authUser()->warehouse" />

    <main>
        <hr class="my-0">
        <section class="pt-5 has-text-centered">
            <h1 class="is-uppercase has-text-grey-dark has-text-weight-bold is-size-4 is-underlined">
                Production Plan
            </h1>
        </section>
        <section class="is-clearfix has-background-white px-6 py-3">
            @foreach ($reportData as $row)
                <section class="py-3">
                    <h2 class="is-size-6 is-pl-12"> Product: <strong>{{ $row->first()['product_name'] }}</strong></h2>
                    <h2 class="is-size-6 is-pl-12"> Quantity: <strong>{{ number_format($row->first()['quantity'], 2) }}</strong></h2>
                    <h2 class="is-size-6 is-pl-12"> Factory: <strong>{{ $row->first()['factory_name'] }}</strong></h2>
                    <h2 class="is-size-6 is-pl-12"> Production Capacity: <strong>{{ number_format($row->min('production_capacity'), 2) }}</strong></h2>
                </section>
                <section class="table-breaked">
                    <h3 class="py-2">Production Capacity Report</h3>
                    <table class="table is-bordered is-hoverable is-fullwidth is-narrow is-size-7">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Raw Material</th>
                                <th>Available Amount </th>
                                <th>Required Amount</th>
                                <th>Difference</th>
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
                                    <td class="has-text-right"> {{ number_format($value['production_capacity'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </section>
            @endforeach
        </section>
    </main>
</body>

</html>
