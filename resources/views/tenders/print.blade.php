<!DOCTYPE html>
<html lang="en" style="background-color: inherit">

<head>
    <meta charset="utf-8">
    <title>Tender #{{ $tender->code . ' - Print Preview' }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css" integrity="sha256-WLKGWSIJYerRN8tbNGtXWVYnUM5wMJTXD8eG4NtGcDM=" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">
    <link rel="shortcut icon" type="image/png" href="{{ asset('img/favicon.png') }}" />
    <style>
        @page {
            size: A4
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
        }

    </style>
</head>

<body class="A4">
    <section class="sheet">
        <article>
            <div class="columns is-marginless has-background-white-ter is-vcentered">
                <div class="column is-3 is-offset-1">
                    <img class="" src="{{ asset('storage/' . $tender->company->logo) }}" style="width: 170px !important; height: 72px !important">
                </div>
                <div class="column is-5 is-offset-3">
                    <h1 class="heading is-capitalized has-text-black has-text-weight-medium is-size-5">
                        {{ $tender->company->name }}
                    </h1>
                    <h1 class="title is-size-7 is-uppercase has-text-grey-light mt-0 mb-0">
                        Tel/Phone
                        <br>
                        <span class="title has-text-weight-normal is-size-6 is-uppercase">
                            {{ $tender->company->phone ?? '-' }}
                        </span>
                    </h1>
                    <h1 class="title is-size-7 has-text-grey-light mb-0">
                        E-mail
                        <br>
                        <span class="title has-text-weight-normal is-size-6">
                            {{ $tender->company->email ?? '-' }}
                        </span>
                    </h1>
                    <h1 class="title is-size-7 has-text-grey-light mb-0">
                        Address
                        <br>
                        <span class="title has-text-weight-normal is-size-6">
                            {{ $tender->company->address ?? '-' }}
                        </span>
                    </h1>
                </div>
            </div>
            <div class="has-text-centered mt-4">
                <h1 class="is-uppercase has-text-grey-dark has-text-weight-bold is-size-4 is-underlined">
                    Tender Checklist
                </h1>
            </div>
            <div class="columns is-marginless">
                <div class="column mx-6 pt-0">
                    <div class="table-container">
                        <table class="table is-bordered is-hoverable is-fullwidth is-narrow is-size-7">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Description</th>
                                    <th>Comment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tender->tenderChecklists as $tenderChecklist)
                                    <tr>
                                        <td> {{ $loop->index + 1 }} </td>
                                        <td> {{ $tenderChecklist->generalTenderChecklist->item }} </td>
                                        <td> {{ $tenderChecklist->comment }} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div style="position:absolute; bottom: 0%;left: 0;right: 0;">
                <div class="columns is-marginless has-background-white-bis">
                    <div class="column py-0 is-4 is-offset-1 is-size-7">
                        <h1 class="title is-size-7 is-uppercase has-text-grey-light mb-6 mt-5">
                            Approved By (with signature)
                        </h1>
                        <div class="mb-3 mt-5" style="border: 1px solid lightgrey"></div>
                    </div>
                </div>
            </div>
        </article>
    </section>

    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/caller.js') }}"></script>

    <script>
        window.onload = window.print();
        window.onafterprint = (event) => {
            event.preventDefault();
            window.close();
        }
    </script>
</body>

</html>
