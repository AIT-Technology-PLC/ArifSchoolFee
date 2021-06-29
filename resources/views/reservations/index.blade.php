@extends('layouts.app')

@section('title')
    Reservation Management
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
                            {{ $totalReservations }}
                        </div>
                        <div class="is-size-7">
                            TOTAL RESERVATIONS
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
                            Create new Reservation to hold products for customers
                        </div>
                        <div class="is-size-3">
                            <a href="{{ route('reservations.create') }}" class="button bg-purple has-text-white has-text-weight-medium is-size-7 px-5 py-4 mt-3">
                                <span class="icon">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span>
                                    Create New Reservation
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
                    {{ $totalConverted }}
                </div>
                <div class="is-uppercase is-size-7">
                    Converted
                </div>
            </div>
        </div>
        <div class="column is-4 p-lr-0">
            <div class="box text-blue has-text-centered" style="border-left: 2px solid #3d6386;">
                <div class="is-size-3 has-text-weight-bold">
                    {{ $totalReserved }}
                </div>
                <div class="is-uppercase is-size-7">
                    Reserved
                </div>
            </div>
        </div>
        <div class="column is-4 p-lr-0">
            <div class="box text-gold has-text-centered" style="border-left: 2px solid #86843d;">
                <div class="is-size-3 has-text-weight-bold">
                    {{ $totalCancelled }}
                </div>
                <div class="is-uppercase is-size-7">
                    Cancelled
                </div>
            </div>
        </div>
        <div class="column is-4 p-lr-0">
            <div class="box text-purple has-text-centered" style="border-left: 2px solid #863d63;">
                <div class="is-size-3 has-text-weight-bold">
                    {{ $totalApproved }}
                </div>
                <div class="is-uppercase is-size-7">
                    Approved
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
        <div class="column is-4 p-lr-0">
            <div class="box bg-blue has-text-white">
                <div class="is-size-3 has-text-weight-bold">
                    {{ userCompany()->currency }} {{ number_format($totalReservedInBirr, 2) }}
                </div>
                <div class="is-uppercase is-size-7">
                    Reservations in Birr
                </div>
            </div>
        </div>
    </div>
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                Reservation Management
            </h1>
        </div>
        <div class="box radius-top-0">
            @include('components.deleted_message', ['model' => 'Reservation'])
            <div>
                <table class="is-hoverable is-size-7 display nowrap" data-date="[7,8]" data-numeric="[]">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th><abbr> Reservation No </abbr></th>
                            <th><abbr> Status </abbr></th>
                            <th><abbr> Payment Method </abbr></th>
                            <th class="has-text-right"><abbr> Total Price </abbr></th>
                            <th><abbr> Customer </abbr></th>
                            <th><abbr> Description </abbr></th>
                            <th class="has-text-right"><abbr> Issued On </abbr></th>
                            <th class="has-text-right"><abbr> Expiry Date </abbr></th>
                            <th><abbr> Prepared By </abbr></th>
                            <th><abbr> Approved By </abbr></th>
                            <th><abbr> Edited By </abbr></th>
                            <th><abbr> Actions </abbr></th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @foreach ($reservations as $reservation)
                            <tr class="showRowDetails is-clickable" data-id="{{ route('reservations.show', $reservation->id) }}">
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized has-text-centered">
                                    {{ $reservation->code }}
                                </td>
                                <td class="is-capitalized">
                                    @if ($reservation->isCancelled())
                                        <span class="tag is-small bg-gold has-text-white">
                                            <span class="icon">
                                                <i class="fas fa-times-circle"></i>
                                            </span>
                                            <span>
                                                Cancelled
                                            </span>
                                        </span>
                                    @elseif ($reservation->isConverted())
                                        <span class="tag is-small bg-green has-text-white">
                                            <span class="icon">
                                                <i class="fas fa-check-circle"></i>
                                            </span>
                                            <span>
                                                Converted
                                            </span>
                                        </span>
                                    @elseif ($reservation->isReserved())
                                        <span class="tag is-small bg-blue has-text-white">
                                            <span class="icon">
                                                <i class="fas fa-check-circle"></i>
                                            </span>
                                            <span>
                                                Reserved
                                            </span>
                                        </span>
                                    @elseif($reservation->isApproved())
                                        <span class="tag is-small bg-purple has-text-white">
                                            <span class="icon">
                                                <i class="fas fa-exclamation-circle"></i>
                                            </span>
                                            <span>
                                                Approved (Not Reserved)
                                            </span>
                                        </span>
                                    @else
                                        <span class="tag is-small bg-purple has-text-white">
                                            <span class="icon">
                                                <i class="fas fa-clock"></i>
                                            </span>
                                            <span>
                                                Waiting Approval
                                            </span>
                                        </span>
                                    @endif
                                </td>
                                <td class="is-capitalized">
                                    {{ $reservation->payment_type ?? 'N/A' }}
                                </td>
                                <td class="has-text-right">
                                    {{ $reservation->company->currency }}.
                                    {{ number_format($reservation->totalPriceWithVAT, 2) }}
                                </td>
                                <td>
                                    {{ $reservation->customer->company_name ?? 'N/A' }}
                                </td>
                                <td class="description">
                                    {!! nl2br(e(substr($reservation->description, 0, 40))) ?? 'N/A' !!}
                                    <span class="is-hidden">
                                        {!! $reservation->description ?? '' !!}
                                    </span>
                                </td>
                                <td class="has-text-right">
                                    {{ $reservation->issued_on->toFormattedDateString() }}
                                </td>
                                <td class="has-text-right">
                                    {{ $reservation->expires_on->toFormattedDateString() }}
                                </td>
                                <td> {{ $reservation->createdBy->name ?? 'N/A' }} </td>
                                <td> {{ $reservation->approvedBy->name ?? 'N/A' }} </td>
                                <td> {{ $reservation->updatedBy->name ?? 'N/A' }} </td>
                                <td class="actions">
                                    <a href="{{ route('reservations.show', $reservation->id) }}" data-title="View Details">
                                        <span class="tag is-white btn-purple is-outlined is-small text-green has-text-weight-medium">
                                            <span class="icon">
                                                <i class="fas fa-info-circle"></i>
                                            </span>
                                            <span>
                                                Details
                                            </span>
                                        </span>
                                    </a>
                                    <a href="{{ route('reservations.edit', $reservation->id) }}" data-title="Modify Reservation Data">
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
                                        @include('components.delete_button', ['model' => 'reservations',
                                        'id' => $reservation->id])
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
