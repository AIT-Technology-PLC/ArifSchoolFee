<!DOCTYPE html>
<html lang="en" style="background-color: inherit">

<head>
    <meta charset="utf-8">
    <title>Return #{{ $return->code . ' - Print Preview' }}</title>
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
            <div class="columns is-marginless has-background-white-ter">
                <div class="column is-3 is-offset-1">
                    <img class="" src="{{ asset('storage/' . $return->company->logo) }}" style="width: 170px !important; height: 72px !important">
                </div>
                <div class="column is-5 is-offset-3">
                    <h1 class="heading is-capitalized has-text-black has-text-weight-medium is-size-5">
                        {{ $return->company->name }}
                    </h1>
                    <h1 class="title is-size-7 is-uppercase has-text-grey-light mt-0 mb-0">
                        Tel/Phone
                        <br>
                        <span class="title has-text-weight-normal is-size-6 is-uppercase">
                            {{ $return->company->phone ?? '-' }}
                        </span>
                    </h1>
                    <h1 class="title is-size-7 has-text-grey-light mb-0">
                        E-mail
                        <br>
                        <span class="title has-text-weight-normal is-size-6">
                            {{ $return->company->email ?? '-' }}
                        </span>
                    </h1>
                    <h1 class="title is-size-7 has-text-grey-light mb-0">
                        Address
                        <br>
                        <span class="title has-text-weight-normal is-size-6">
                            {{ $return->company->address ?? '-' }}
                        </span>
                    </h1>
                </div>
            </div>
            @if ($return->customer)
                <div class="columns is-marginless has-background-white-bis">
                    <div class="column is-offset-1 is-size-7">
                        <h1 class="title is-size-7 is-uppercase has-text-grey-light mb-0">
                            Customer
                            <br>
                            <span class="title is-size-6 is-uppercase">
                                {{ $return->customer->company_name ?? '-' }}
                            </span>
                            <span class="has-text-weight-normal has-text-grey-dark is-size-6 is-capitalized {{ $return->customer->tin ? '' : 'is-hidden' }}">
                                <br>
                                TIN: {{ $return->customer->tin ?? '-' }}
                            </span>
                            <span class="has-text-weight-normal has-text-grey-dark is-size-6 is-capitalized {{ $return->customer->address ? '' : 'is-hidden' }}">
                                <br>
                                Address: {{ $return->customer->address ?? '-' }}
                            </span>
                        </h1>
                    </div>
                </div>
            @endif
            <div class="columns is-marginless has-background-white-bis">
                <div class="column is-3 is-offset-1">
                    <h1 class="title is-size-7 is-uppercase has-text-grey-light">
                        No
                        <br>
                        <span class="title is-size-6 is-uppercase">
                            {{ $return->code }}
                        </span>
                    </h1>
                </div>
                <div class="column is-4">
                    <h1 class="title is-size-7 is-uppercase has-text-grey-light">
                        Issued On
                        <br>
                        <span class="title is-size-6 is-capitalized">
                            {{ $return->issued_on->toFormattedDateString() }}
                        </span>
                    </h1>
                </div>
            </div>
            <div class="has-text-centered mt-4">
                <h1 class="is-uppercase has-text-grey-dark has-text-weight-bold is-size-4 is-underlined">
                    Return Voucher
                </h1>
            </div>
            <div class="columns is-marginless">
                <div class="column mx-6 pt-0">
                    <div class="table-container">
                        <table class="table is-bordered is-hoverable is-fullwidth is-narrow is-size-7">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product Description</th>
                                    <th>Category</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($return->returnDetails as $returnDetail)
                                    <tr>
                                        <td> {{ $loop->index + 1 }} </td>
                                        <td> {{ $returnDetail->product->name }} </td>
                                        <td> {{ $returnDetail->product->productCategory->name }} </td>
                                        <td> {{ number_format($returnDetail->quantity, 2) }} {{ $returnDetail->product->unit_of_measurement }} </td>
                                        <td> {{ number_format($returnDetail->unit_price, 2) }} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="columns is-marginless">
                <div class="column mx-6 pt-0">
                    <div class="table-container">
                        <table class="table is-bordered is-striped is-hoverable is-fullwidth is-size-7">
                            <tbody>
                                <tr>
                                    <td class="has-text-weight-bold">Sub-Total</td>
                                    <td class="has-text-right">{{ number_format($return->totalCredit, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="has-text-weight-bold">VAT 15%</td>
                                    <td class="has-text-right">{{ number_format($return->totalCredit * 0.15, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="has-text-weight-bold">Grand Total</td>
                                    <td class="has-text-right has-text-weight-bold">{{ number_format($return->totalCreditAfterVAT, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div style="position:absolute; bottom: 0%;left: 0;right: 0;">
                <div class="columns is-marginless has-background-white-ter">
                    <div class="column py-0 is-4 is-offset-1 is-size-7">
                        <h1 class="title is-size-7 is-uppercase has-text-grey-light mb-0 mt-6">
                            Prepared By
                            <br>
                            <span class="title is-size-6 is-uppercase">
                                {{ $return->createdBy->name }}
                            </span>
                        </h1>
                        <h1 class="title is-size-7 is-uppercase has-text-grey-light mb-6 mt-5">
                            Signature
                        </h1>
                        <div class="mb-3" style="border: 1px solid lightgrey"></div>
                    </div>
                    <div class="column py-0 is-4 is-offset-2">
                        <h1 class="title is-size-7 is-uppercase has-text-grey-light mb-0 mt-6">
                            Approved By
                            <br>
                            <span class="title is-size-6 is-uppercase">
                                {{ $return->approvedBy->name ?? 'Still Not Approved' }}
                            </span>
                        </h1>
                        <h1 class="title is-size-7 is-uppercase has-text-grey-light mb-6 mt-5">
                            Signature
                        </h1>
                        <div class="mb-3" style="border: 1px solid lightgrey"></div>
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
