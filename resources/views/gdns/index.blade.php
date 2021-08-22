@extends('layouts.app')

@section('title')
    Delivery Order Management
@endsection

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-6 p-lr-0">
            <div class="box text-green">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column has-text-centered is-paddingless">
                        <span class="icon is-large is-size-1">
                            <i class="fas fa-file-invoice"></i>
                        </span>
                    </div>
                    <div class="column is-paddingless">
                        <div class="is-size-3 has-text-weight-bold">
                            {{ $totalGdns }}
                        </div>
                        <div class="is-size-7">
                            TOTAL DELIVERY ORDERS
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
                            Create new Delivery Order for new sales
                        </div>
                        <div class="is-size-3">
                            <a href="{{ route('gdns.create') }}" class="button bg-purple has-text-white has-text-weight-medium is-size-7 px-5 py-4 mt-3">
                                <span class="icon">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span>
                                    Create New DO
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
                    {{ $totalSubtracted }}
                </div>
                <div class="is-uppercase is-size-7">
                    Subtracted
                </div>
            </div>
        </div>
        <div class="column is-4 p-lr-0">
            <div class="box text-gold has-text-centered" style="border-left: 2px solid #86843d;">
                <div class="is-size-3 has-text-weight-bold">
                    {{ $totalNotSubtracted }}
                </div>
                <div class="is-uppercase is-size-7">
                    Approved (not Subtracted)
                </div>
            </div>
        </div>
        <div class="column is-4 p-lr-0">
            <div class="box text-purple has-text-centered" style="border-left: 2px solid #863d63;">
                <div class="is-size-3 has-text-weight-bold">
                    {{ $totalNotApproved }}
                </div>
                <div class="is-uppercase is-size-7">
                    Waiting Approval
                </div>
            </div>
        </div>
    </div>
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                Delivery Order Management
            </h1>
        </div>
        <div class="box radius-top-0">
            @include('components.deleted_message', ['model' => 'DO'])
            <div>
                <table class="regular-datatable is-hoverable is-size-7 display nowrap" data-date="[{{ $enabledFeatures->contains('Sale Management') ? 8 : 7 }}]" data-numeric="[]">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th><abbr> DO No </abbr></th>
                            @if ($enabledFeatures->contains('Sale Management'))
                                <th><abbr> Receipt No </abbr></th>
                            @endif
                            <th><abbr> Status </abbr></th>
                            <th><abbr> Payment Method </abbr></th>
                            <th class="has-text-right"><abbr> Total Price </abbr></th>
                            <th><abbr> Customer </abbr></th>
                            <th><abbr> Description </abbr></th>
                            <th class="has-text-right"><abbr> Issued On </abbr></th>
                            <th><abbr> Prepared By </abbr></th>
                            <th><abbr> Approved By </abbr></th>
                            <th><abbr> Edited By </abbr></th>
                            <th><abbr> Actions </abbr></th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @foreach ($gdns as $gdn)
                            <tr class="showRowDetails is-clickable" data-id="{{ route('gdns.show', $gdn->id) }}">
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized has-text-centered">
                                    {{ $gdn->code }}
                                </td>
                                @if ($enabledFeatures->contains('Sale Management'))
                                    <td class="is-capitalized">
                                        {{ is_null($gdn->sale) ? 'N/A' : $gdn->sale->receipt_no }}
                                    </td>
                                @endif
                                <td class="is-capitalized">
                                    @if (!$gdn->isApproved())
                                        <span class="tag is-small bg-purple has-text-white">
                                            <span class="icon">
                                                <i class="fas fa-clock"></i>
                                            </span>
                                            <span>
                                                Waiting Approval
                                            </span>
                                        </span>
                                    @elseif ($gdn->isSubtracted())
                                        <span class="tag is-small bg-green has-text-white">
                                            <span class="icon">
                                                <i class="fas fa-check-circle"></i>
                                            </span>
                                            <span>
                                                Subtracted
                                            </span>
                                        </span>
                                    @else
                                        <span class="tag is-small bg-gold has-text-white">
                                            <span class="icon">
                                                <i class="fas fa-exclamation-circle"></i>
                                            </span>
                                            <span>
                                                Approved (not Subtracted)
                                            </span>
                                        </span>
                                    @endif
                                </td>
                                <td class="is-capitalized">
                                    {{ $gdn->payment_type ?? 'N/A' }}
                                </td>
                                <td class="has-text-right">
                                    {{ $gdn->company->currency }}.
                                    @if (userCompany()->isDiscountBeforeVAT())
                                        {{ number_format($gdn->grandTotalPrice, 2) }}
                                    @else
                                        {{ number_format($gdn->grandTotalPriceAfterDiscount, 2) }}
                                    @endif
                                </td>
                                <td>
                                    {{ $gdn->customer->company_name ?? 'N/A' }}
                                </td>
                                <td class="description">
                                    {!! is_null($gdn->description) ? 'N/A' : substr(strip_tags($gdn->description), 0, 20) . '...' !!}
                                    <span class="is-hidden">
                                        {!! $gdn->description ?? '' !!}
                                    </span>
                                </td>
                                <td class="has-text-right">
                                    {{ $gdn->issued_on->toFormattedDateString() }}
                                </td>
                                <td> {{ $gdn->createdBy->name ?? 'N/A' }} </td>
                                <td> {{ $gdn->approvedBy->name ?? 'N/A' }} </td>
                                <td> {{ $gdn->updatedBy->name ?? 'N/A' }} </td>
                                <td class="actions">
                                    <a href="{{ route('gdns.show', $gdn->id) }}" data-title="View Details">
                                        <span class="tag is-white btn-purple is-outlined is-small text-green has-text-weight-medium">
                                            <span class="icon">
                                                <i class="fas fa-info-circle"></i>
                                            </span>
                                            <span>
                                                Details
                                            </span>
                                        </span>
                                    </a>
                                    <a href="{{ route('gdns.edit', $gdn->id) }}" data-title="Modify DO Data">
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
                                        @include('components.delete_button', ['model' => 'gdns',
                                        'id' => $gdn->id])
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
