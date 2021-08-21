<!DOCTYPE html>
<html lang="en" style="background-color: inherit">

<head>
    <meta charset="utf-8">
    <title>DO/GDN #{{ $gdn->code . ' - Print Preview' }}</title>
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
                    <img class="" src="{{ asset('storage/' . $gdn->company->logo) }}" style="width: 170px !important; height: 72px !important">
                </div>
                <div class="column is-5 is-offset-3">
                    <h1 class="heading is-capitalized has-text-black has-text-weight-medium is-size-5">
                        {{ $gdn->company->name }}
                    </h1>
                    <h1 class="title is-size-7 is-uppercase has-text-grey-light mt-0 mb-0">
                        Tel/Phone
                        <br>
                        <span class="title has-text-weight-normal is-size-6 is-uppercase">
                            {{ $gdn->company->phone ?? '-' }}
                        </span>
                    </h1>
                    <h1 class="title is-size-7 has-text-grey-light mb-0">
                        E-mail
                        <br>
                        <span class="title has-text-weight-normal is-size-6">
                            {{ $gdn->company->email ?? '-' }}
                        </span>
                    </h1>
                    <h1 class="title is-size-7 has-text-grey-light mb-0">
                        Address
                        <br>
                        <span class="title has-text-weight-normal is-size-6">
                            {{ $gdn->company->address ?? '-' }}
                        </span>
                    </h1>
                </div>
            </div>
            @if ($gdn->customer)
                <div class="columns is-marginless has-background-white-bis">
                    <div class="column is-offset-1 is-size-7">
                        <h1 class="title is-size-7 is-uppercase has-text-grey-light mb-0">
                            Customer
                            <br>
                            <span class="title is-size-6 is-uppercase">
                                {{ $gdn->customer->company_name ?? '-' }}
                            </span>
                            <span class="has-text-weight-normal has-text-grey-dark is-size-6 is-capitalized {{ $gdn->customer->tin ? '' : 'is-hidden' }}">
                                <br>
                                TIN: {{ $gdn->customer->tin ?? '-' }}
                            </span>
                            <span class="has-text-weight-normal has-text-grey-dark is-size-6 is-capitalized {{ $gdn->customer->address ? '' : 'is-hidden' }}">
                                <br>
                                Address: {{ $gdn->customer->address ?? '-' }}
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
                            {{ $gdn->code }}
                        </span>
                    </h1>
                </div>
                <div class="column is-4">
                    <h1 class="title is-size-7 is-uppercase has-text-grey-light">
                        Issued On
                        <br>
                        <span class="title is-size-6 is-capitalized">
                            {{ $gdn->issued_on->toFormattedDateString() }}
                        </span>
                    </h1>
                </div>
                <div class="column is-4">
                    <h1 class="title is-size-7 is-uppercase has-text-grey-light">
                        Payment Type
                        <br>
                        <span class="title is-size-6 is-capitalized">
                            {{ $gdn->payment_type }}
                        </span>
                    </h1>
                </div>
            </div>
            <div class="has-text-centered mt-4">
                <h1 class="is-uppercase has-text-grey-dark has-text-weight-bold is-size-4 is-underlined">
                    Delivery Note
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
                                    @if (userCompany()->isDiscountBeforeVAT())
                                        <th>Discount</th>
                                    @endif
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($gdn->gdnDetails as $gdnDetail)
                                    <tr>
                                        <td> {{ $loop->index + 1 }} </td>
                                        <td> {{ $gdnDetail->product->name }} </td>
                                        <td> {{ $gdnDetail->product->productCategory->name }} </td>
                                        <td> {{ number_format($gdnDetail->quantity, 2) }} {{ $gdnDetail->product->unit_of_measurement }} </td>
                                        <td> {{ number_format($gdnDetail->unit_price, 2) }} </td>
                                        @if (userCompany()->isDiscountBeforeVAT())
                                            <td class="has-text-right"> {{ number_format($gdnDetail->discount * 100, 2) }}% </td>
                                        @endif
                                        <td class="has-text-right"> {{ number_format($gdnDetail->totalPrice, 2) }} </td>
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
                                    <td colspan="2" class="has-text-weight-bold">Payment Details</td>
                                </tr>
                                <tr>
                                    <td>
                                        <p>
                                            <span class="has-text-weight-bold is-uppercase">
                                                In Cash ({{ (float) $gdn->cash_received_in_percentage }}%)
                                            </span>
                                            <br>
                                            <span>
                                                {{ number_format($gdn->getPaymentInCash(), 2) }}
                                            </span>
                                        </p>
                                    </td>
                                    <td>
                                        <p>
                                            <span class="has-text-weight-bold is-uppercase">
                                                On Credit ({{ $gdn->credit_payable_in_percentage }}%)
                                            </span>
                                            <br>
                                            <span>
                                                {{ number_format($gdn->getPaymentInCredit(), 2) }}
                                            </span>
                                        </p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="column mx-6 pt-0">
                    <div class="table-container">
                        <table class="table is-bordered is-striped is-hoverable is-fullwidth is-size-7">
                            <tbody>
                                <tr>
                                    <td class="has-text-weight-bold">Sub-Total</td>
                                    <td class="has-text-right">{{ number_format($gdn->subtotalPrice, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="has-text-weight-bold">VAT 15%</td>
                                    <td class="has-text-right">{{ number_format($gdn->vat, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="has-text-weight-bold">Grand Total</td>
                                    <td class="has-text-right has-text-weight-bold">{{ number_format($gdn->grandTotalPrice, 2) }}</td>
                                </tr>
                                @if (!userCompany()->isDiscountBeforeVAT())
                                    <tr>
                                        <td colspan="4" class="is-borderless"></td>
                                        <td class="has-text-weight-bold">Discount</td>
                                        <td class="has-text-right has-text-weight-bold">{{ number_format($gdn->discount * 100, 2) }}%</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="is-borderless"></td>
                                        <td class="has-text-weight-bold">
                                            Grand Total
                                            <br>
                                            <span class="has-text-grey">
                                                After Discount
                                            </span>
                                        </td>
                                        <td class="has-text-right has-text-weight-bold">{{ number_format($gdn->grandTotalPriceAfterDiscount, 2) }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div style="position:absolute; bottom: 0%;left: 0;right: 0;">
                <div class="columns is-marginless has-background-white-bis">
                    <div class="column is-10 is-offset-1 py-0 is-size-7">
                        <h1 class="title is-size-7 is-uppercase my-6">
                            I received the above goods/services in good condition
                            <br>
                        </h1>
                        <div class="mb-5" style="border: 1px solid lightgrey;width: 55%"></div>
                    </div>
                </div>
                @if ($gdn->created_by == $gdn->approved_by)
                    <div class="columns is-marginless has-background-white-ter">
                        <div class="column py-0 is-4 is-offset-1 is-size-7">
                            <h1 class="title is-size-7 is-uppercase has-text-grey-light mb-0 mt-6">
                                Prepared & Approved By
                                <br>
                                <span class="title is-size-6 is-uppercase">
                                    {{ $gdn->createdBy->name }}
                                </span>
                            </h1>
                            <h1 class="title is-size-7 is-uppercase has-text-grey-light mb-6 mt-5">
                                Signature
                            </h1>
                            <div class="mb-3" style="border: 1px solid lightgrey"></div>
                        </div>
                    </div>
                @else
                    <div class="columns is-marginless has-background-white-ter">
                        <div class="column py-0 is-4 is-offset-1 is-size-7">
                            <h1 class="title is-size-7 is-uppercase has-text-grey-light mb-0 mt-6">
                                Prepared By
                                <br>
                                <span class="title is-size-6 is-uppercase">
                                    {{ $gdn->createdBy->name }}
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
                                    {{ $gdn->approvedBy->name ?? 'Still Not Approved' }}
                                </span>
                            </h1>
                            <h1 class="title is-size-7 is-uppercase has-text-grey-light mb-6 mt-5">
                                Signature
                            </h1>
                            <div class="mb-3" style="border: 1px solid lightgrey"></div>
                        </div>
                    </div>
                @endif
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
