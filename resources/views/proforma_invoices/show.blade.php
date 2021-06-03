@extends('layouts.app')

@section('title')
    Proforma Invoice Details
@endsection

@section('content')
    <div class="box mt-3 mx-3 m-lr-0">
        <div class="columns is-marginless is-multiline">
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-file-invoice"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $proformaInvoice->code ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Proforma Invoice No
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-user"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $proformaInvoice->customer->company_name ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Customer
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-calendar-day"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $proformaInvoice->issued_on->toFormattedDateString() ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Issued On
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-calendar-day"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $proformaInvoice->expires_on->toFormattedDateString() ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Expiry Date
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-dollar-sign"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ number_format($proformaInvoice->totalPrice, 2) }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Total Price ({{ $proformaInvoice->company->currency }})
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-purple">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-dollar-sign"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ number_format($proformaInvoice->totalPriceAfterVAT, 2) }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Total Price with VAT ({{ $proformaInvoice->company->currency }})
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <div>
                    <div class="columns is-marginless is-vcentered text-green">
                        <div class="column">
                            <div class="has-text-weight-bold">
                                Terms and Conditions
                            </div>
                            <div class="is-size-7 mt-3">
                                {!! $proformaInvoice->terms !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <div class="level">
                <div class="level-left">
                    <div class="level-item is-justify-content-left">
                        <div>
                            <h1 class="title text-green has-text-weight-medium is-size-5">
                                Proforma Invoice Details
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-item is-justify-content-left">
                        <div>
                            @if (!$proformaInvoice->isCancelled())
                                <button id="printGdn" class="button is-small bg-purple has-text-white is-hidden-mobile" onclick="openInNewTab('/proforma-invoices/{{ $proformaInvoice->id }}/print')">
                                    <span class="icon">
                                        <i class="fas fa-print"></i>
                                    </span>
                                    <span>
                                        Print
                                    </span>
                                </button>
                            @endif
                            <a href="{{ route('proforma-invoices.edit', $proformaInvoice->id) }}" class="button is-small bg-green has-text-white">
                                <span class="icon">
                                    <i class="fas fa-pen"></i>
                                </span>
                                <span>
                                    Edit
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box radius-bottom-0 mb-0 radius-top-0">
            <div class="notification bg-lightpurple text-purple {{ session('failedMessage') ? '' : 'is-hidden' }}">
                @foreach ((array) session('failedMessage') as $message)
                    <span class="icon">
                        <i class="fas fa-times-circle"></i>
                    </span>
                    <span>
                        {{ $message }}
                    </span>
                    <br>
                @endforeach
            </div>
            <div class="notification bg-green has-text-white has-text-weight-medium {{ session('successMessage') ? '' : 'is-hidden' }}">
                <span class="icon">
                    <i class="fas fa-check-circle"></i>
                </span>
                <span>
                    {{ session('successMessage') }}
                </span>
            </div>
            @if ($proformaInvoice->isCancelled())
                <div class="box is-shadowless bg-lightpurple has-text-left mb-6">
                    <p class="text-purple is-size-6">
                        <span class="icon">
                            <i class="fas fa-times-circle"></i>
                        </span>
                        <span>
                            This Proforma Invoice is cancelled.
                        </span>
                    </p>
                </div>
            @endif
            @if ($proformaInvoice->isConverted())
                <div class="box is-shadowless bg-lightgreen has-text-left mb-6">
                    <p class="text-purple is-size-6">
                        <span class="icon">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        <span>
                            This Proforma Invoice has been completed successfully.
                        </span>
                    </p>
                </div>
            @endif
            @if ($proformaInvoice->isPending())
                <div class="box is-shadowless bg-lightpurple has-text-left mb-6">
                    <p class="text-purple is-size-6">
                        <span class="icon">
                            <i class="fas fa-exclamation-circle"></i>
                        </span>
                        <span>
                            This Proforma Invoice is still pending.
                        </span>
                    </p>
                    @can('Convert Proforma Invoice')
                        <form id="formOne" action="{{ route('proforma-invoices.convert', $proformaInvoice->id) }}" method="post" novalidate class="is-inline">
                            @csrf
                            <button id="openApproveGdnModal" class="button bg-purple has-text-white mt-5 is-size-7-mobile">
                                <span class="icon">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                                <span>
                                    Confirm
                                </span>
                            </button>
                        </form>
                    @endcan
                    @can('Cancel Proforma Invoice')
                        <form id="formOne" action="{{ route('proforma-invoices.cancel', $proformaInvoice->id) }}" method="post" novalidate class="is-inline">
                            @csrf
                            <button id="openApproveGdnModal" class="button bg-lightpurple text-purple mt-5 is-size-7-mobile">
                                <span class="icon">
                                    <i class="fas fa-times"></i>
                                </span>
                                <span>
                                    Cancel
                                </span>
                            </button>
                        </form>
                    @endcan
                </div>
            @endif
            <div class="table-container">
                <table class="table is-hoverable is-fullwidth is-size-7">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th><abbr> Product </abbr></th>
                            <th><abbr> Quantity </abbr></th>
                            <th><abbr> Unit Price </abbr></th>
                            <th><abbr> Discount </abbr></th>
                            <th><abbr> Total </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($proformaInvoice->proformaInvoiceDetails as $proformaInvoiceDetail)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized">
                                    {{ $proformaInvoiceDetail->product->name }}
                                </td>
                                <td>
                                    {{ number_format($proformaInvoiceDetail->quantity, 2) }}
                                    {{ $proformaInvoiceDetail->product->unit_of_measurement }}
                                </td>
                                <td>
                                    {{ $proformaInvoice->company->currency }}.
                                    {{ number_format($proformaInvoiceDetail->unit_price, 2) }}
                                </td>
                                <td>
                                    {{ number_format($proformaInvoiceDetail->discount * 100, 2) }}%
                                </td>
                                <td>
                                    {{ number_format($proformaInvoiceDetail->unitPriceAfterDiscount, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
