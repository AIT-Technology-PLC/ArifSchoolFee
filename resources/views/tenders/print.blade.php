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
    <style>
        .page-break {
            page-break-inside: avoid;
        }

        @media print {
            .table-breaked {
                page-break-before: auto;
            }
        }

        td {
            padding-top: 0.25rem !important;
            padding-bottom: 0.25rem !important;
        }

    </style>
</head>

<body>
    <x-print.header />

    <main>
        <section class="pt-5 has-text-centered">
            <h1 class="is-uppercase has-text-grey-dark has-text-weight-bold is-size-4 is-underlined">
                Tender Checklist
            </h1>
        </section>

        <section class="px-6 table-breaked">
            <table class="table is-bordered is-hoverable is-fullwidth is-narrow is-size-7">
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

    </main>

    <footer style="position:absolute;bottom: 0%;left: 0;right: 0;margin-top: 132px">
        <aside class="has-text-centered">
            <h1 class="is-size-7 is-uppercase has-text-grey-light mb-5 mt-5">
                Approved By
            </h1>
            <div
                class="mb-3"
                style="border-bottom: 1px solid lightgrey;width: 20%;margin-left:40%"
            >&nbsp;</div>
        </aside>
    </footer>
</body>

</html>
