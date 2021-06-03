@extends('layouts.app')

@section('title')
    Proforma Invoices
@endsection

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-6 p-lr-0">
            <div class="box text-green">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column has-text-centered is-paddingless">
                        <span class="icon is-large is-size-1">
                            <i class="fas fa-receipt"></i>
                        </span>
                    </div>
                    <div class="column is-paddingless">
                        <div class="is-size-3 has-text-weight-bold">
                            {{ $totalProformaInvoices }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            Total Proforma Invoices
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="column is-6 p-lr-0">
            <div class="box text-purple">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column is-paddingless has-text-centered">
                        <div class="is-uppercase is-size-7">
                            Create new proforma invoice, print and convert
                        </div>
                        <div class="is-size-3">
                            <a href="{{ route('proforma-invoices.create') }}" class="button bg-purple has-text-white has-text-weight-medium is-size-7 px-5 py-4 mt-3">
                                <span class="icon">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span>
                                    Create New PI
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="column is-4 p-lr-0">
            <div class="box text-green has-text-centered" style="border-left: 2px solid #3d8660;">
                <div class="is-size-3 has-text-weight-bold">
                    {{ $totalProformaInvoices }}
                </div>
                <div class="is-uppercase is-size-7">
                    Pending
                </div>
            </div>
        </div>
        <div class="column is-4 p-lr-0">
            <div class="box text-gold has-text-centered" style="border-left: 2px solid #86843d;">
                <div class="is-size-3 has-text-weight-bold">
                    {{ $totalProformaInvoices }}
                </div>
                <div class="is-uppercase is-size-7">
                    Converted
                </div>
            </div>
        </div>
        <div class="column is-4 p-lr-0">
            <div class="box text-purple has-text-centered" style="border-left: 2px solid #863d63;">
                <div class="is-size-3 has-text-weight-bold">
                    {{ $totalProformaInvoices }}
                </div>
                <div class="is-uppercase is-size-7">
                    Cancelled
                </div>
            </div>
        </div>
    </div>
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                Proforma Invoices
            </h1>
        </div>
        <div class="box radius-top-0">
            @include('components.deleted_message', ['model' => 'Sale'])
            <div>
                <table id="table_id" class="is-hoverable is-size-7 display nowrap" data-date="[5]" data-numeric="[4]">
                    <thead>
                        <tr>
                            <th id="firstTarget"><abbr> # </abbr></th>
                            <th><abbr> PI N<u>o</u> </abbr></th>
                            <th><abbr> Status </abbr></th>
                            <th><abbr> Customer </abbr></th>
                            <th><abbr> Issued on </abbr></th>
                            <th><abbr> Expiry Date </abbr></th>
                            <th><abbr> Prepared By </abbr></th>
                            <th><abbr> Converted By </abbr></th>
                            <th><abbr> Edited By </abbr></th>
                            <th><abbr> Actions </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($proformaInvoices as $proformaInvoice)
                            <tr class="showRowDetails is-clickable" data-id="{{ route('proforma-invoices.show', $proformaInvoice->id) }}">
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized">
                                    {{ $proformaInvoice->code ?? 'N/A' }}
                                </td>
                                <td class="is-capitalized">
                                    {{ $proformaInvoice->status }}
                                </td>
                                <td>
                                    {{ $proformaInvoice->customer->name }}
                                </td>
                                <td>
                                    {{ $proformaInvoice->issued_on->toFormattedDateString() }}
                                </td>
                                <td>
                                    {{ $proformaInvoice->expires_on->toFormattedDateString() }}
                                </td>
                                <td> {{ $proformaInvoice->createdBy->name ?? 'N/A' }} </td>
                                <td> {{ $proformaInvoice->convertedBy->name ?? 'N/A' }} </td>
                                <td> {{ $proformaInvoice->updatedBy->name ?? 'N/A' }} </td>
                                <td class="actions">
                                    <a href="{{ route('proforma-invoices.show', $proformaInvoice->id) }}" data-title="View Details">
                                        <span class="tag is-white btn-purple is-outlined is-small text-green has-text-weight-medium">
                                            <span class="icon">
                                                <i class="fas fa-info-circle"></i>
                                            </span>
                                            <span>
                                                Details
                                            </span>
                                        </span>
                                    </a>
                                    <a href="{{ route('proforma-invoices.edit', $proformaInvoice->id) }}" data-title="Modify Proforma Invoice Data">
                                        <span class="tag is-white btn-green is-outlined is-small text-green has-text-weight-medium">
                                            <span class="icon">
                                                <i class="fas fa-pen-square"></i>
                                            </span>
                                            <span>
                                                Edit
                                            </span>
                                        </span>
                                    </a>
                                    <span>
                                        @include('components.delete_button', ['model' => 'proforma-invoices',
                                        'id' => $proformaInvoice->id])
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
