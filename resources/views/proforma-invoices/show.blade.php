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
                                {{ $proformaInvoice->reference ?? 'N/A' }}
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
                                {{ number_format($proformaInvoice->subtotalPrice, 2) }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                SubTotal Price ({{ userCompany()->currency }})
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
                                {{ number_format($proformaInvoice->grandTotalPrice, 2) }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Grand Total Price ({{ userCompany()->currency }})
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (!userCompany()->isDiscountBeforeVAT())
                <div class="column is-6">
                    <div>
                        <div class="columns is-marginless is-vcentered is-mobile text-green">
                            <div class="column is-1">
                                <span class="icon is-size-3">
                                    <i class="fas fa-percentage"></i>
                                </span>
                            </div>
                            <div class="column m-lr-20">
                                <div class="is-size- has-text-weight-bold">
                                    {{ number_format($proformaInvoice->discount * 100, 2) }}%
                                </div>
                                <div class="is-uppercase is-size-7">
                                    Discount
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
                                    {{ number_format($proformaInvoice->grandTotalPriceAfterDiscount, 2) }}
                                </div>
                                <div class="is-uppercase is-size-7">
                                    Grand Total Price (After Discount)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
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
                            @if ($proformaInvoice->isConverted() && !$proformaInvoice->isClosed())
                                <x-common.transaction-button
                                    :route="route('proforma-invoices.close', $proformaInvoice->id)"
                                    action="close"
                                    intention="close this proforma invoice"
                                    icon="fas fa-ban"
                                    label="Close"
                                />
                                @can('Create GDN')
                                    <a
                                        href="{{ route('proforma-invoices.convert_to_gdn', $proformaInvoice->id) }}"
                                        class="button btn-purple is-outlined is-small"
                                    >
                                        <span class="icon">
                                            <i class="fas fa-file-invoice"></i>
                                        </span>
                                        <span>
                                            Convert to DO
                                        </span>
                                    </a>
                                @endcan
                            @endif
                            @if (!$proformaInvoice->isCancelled())
                                <a
                                    class="button btn-purple is-outlined is-small is-hidden-mobile"
                                    href="{{ route('proforma-invoices.print', $proformaInvoice->id) }}"
                                    target="_blank"
                                >
                                    <span class="icon">
                                        <i class="fas fa-print"></i>
                                    </span>
                                    <span>
                                        Print
                                    </span>
                                </a>
                            @endif
                            <a
                                href="{{ route('proforma-invoices.edit', $proformaInvoice->id) }}"
                                class="button is-small bg-green has-text-white"
                            >
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
            <x-common.success-message :message="session('successMessage')" />
            @if ($proformaInvoice->isCancelled())
                <x-common.fail-message message="This Proforma Invoice has been cancelled." />
            @elseif ($proformaInvoice->isConverted())
                <x-common.success-message message="This Proforma Invoice has been confirmed." />
            @elseif ($proformaInvoice->isPending())
                <x-common.fail-message message="This Proforma Invoice is still pending.">
                    @can('Convert Proforma Invoice')
                        <form
                            x-data="swal('confirm', 'confirm this proforma invoice')"
                            action="{{ route('proforma-invoices.convert', $proformaInvoice->id) }}"
                            method="post"
                            novalidate
                            class="is-inline"
                            @submit.prevent="open"
                        >
                            @csrf
                            <button
                                class="button bg-purple has-text-white mt-5 is-size-7-mobile"
                                x-ref="submitButton"
                            >
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
                        <form
                            x-data="swal('cancel', 'cancel this proforma invoice')"
                            action="{{ route('proforma-invoices.cancel', $proformaInvoice->id) }}"
                            method="post"
                            novalidate
                            class="is-inline"
                            @submit.prevent="open"
                        >
                            @csrf
                            <button
                                class="button bg-lightpurple text-purple mt-5 is-size-7-mobile"
                                x-ref="submitButton"
                            >
                                <span class="icon">
                                    <i class="fas fa-times"></i>
                                </span>
                                <span>
                                    Cancel
                                </span>
                            </button>
                        </form>
                    @endcan
                </x-common.fail-message>
            @endif
            <x-common.success-message :message="session('deleted')" />
            <div>
                {{ $dataTable->table() }}
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
