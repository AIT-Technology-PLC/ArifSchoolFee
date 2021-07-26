<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> Tender "{{ $tender->code }}" - {{ userCompany()->name }} </title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('img/favicon.png') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css" integrity="sha256-WLKGWSIJYerRN8tbNGtXWVYnUM5wMJTXD8eG4NtGcDM=" crossorigin="anonymous">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .page-break {
            page-break-inside: avoid;
        }

        @media print {
            .table-breaked {
                page-break-before: auto;
            }
        }

    </style>
</head>

<body>
    <header class="is-clearfix py-5 has-background-white-ter">
        <aside class="is-pulled-left ml-6 mt-6 pt-4">
            <img src="{{ asset('storage/' . $tender->company->logo) }}" width="30%">
        </aside>
        <aside class="is-pulled-right mr-6">
            <h1 class="heading is-capitalized has-text-black has-text-weight-medium is-size-5">
                {{ $tender->company->name }}
            </h1>
            <h1 class="is-uppercase has-text-grey-light has-text-weight-dark is-size-7 mb-0">
                Tel/Phone
            </h1>
            <p class="has-text-grey-dark has-text-weight-medium is-size-6">
                {{ $tender->company->phone ?? '-' }}
            </p>
            <h1 class="is-uppercase has-text-grey-light has-text-weight-dark is-size-7">
                Email
            </h1>
            <p class="has-text-grey-dark has-text-weight-medium is-size-6">
                {{ $tender->company->email ?? '-' }}
            </p>
            <h1 class="is-uppercase has-text-grey-light has-text-weight-dark is-size-7">
                Address
            </h1>
            <p class="has-text-grey-dark has-text-weight-medium is-size-6">
                {{ $tender->company->address ?? '-' }}
            </p>
        </aside>
    </header>

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
                        <td colspan="3" class="is-borderless">&nbsp;</td>
                    </tr>
                    <tr class="is-borderless">
                        <td colspan="3" class="is-borderless">&nbsp;</td>
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

    <footer class="has-background-white-ter" style="position:absolute;bottom: 0%;left: 0;right: 0;margin-top: 132px">
        <aside class="has-text-centered">
            <h1 class="is-size-7 is-uppercase has-text-grey-light mb-5 mt-5">
                Approved By
            </h1>
            <div class="mb-3" style="border-bottom: 1px solid lightgrey;width: 20%;margin-left:40%">&nbsp;</div>
        </aside>
    </footer>
</body>

</html>
