@extends('layouts.app')

@section('title')
    Reservation Details
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
                                {{ $reservation->code ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Reservation No
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
                                <i class="fas fa-credit-card"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $reservation->payment_type ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Payment Type
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
                                {{ $reservation->customer->company_name ?? 'N/A' }}
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
                                {{ $reservation->issued_on->toFormattedDateString() ?? 'N/A' }}
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
                                <i class="fas fa-calendar-times"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $reservation->expires_on->toFormattedDateString() ?? 'N/A' }}
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
                                {{ number_format($reservation->totalPrice, 2) }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Total Price ({{ $reservation->company->currency }})
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
                                <i class="fas fa-hand-holding-usd"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ number_format($reservation->getPaymentInCash(), 2) }}
                                ({{ (float) $reservation->cash_received_in_percentage }}%)
                            </div>
                            <div class="is-uppercase is-size-7">
                                In Cash ({{ $reservation->company->currency }})
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
                                <i class="fas fa-money-check"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ number_format($reservation->getPaymentInCredit(), 2) }}
                                ({{ $reservation->credit_payable_in_percentage }}%)
                            </div>
                            <div class="is-uppercase is-size-7">
                                On Credit ({{ $reservation->company->currency }})
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
                                {{ number_format($reservation->totalPriceWithVAT, 2) }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Total Price with VAT ({{ $reservation->company->currency }})
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
                                Details
                            </div>
                            <div class="is-size-7 mt-3">
                                {!! is_null($reservation->description) ? 'N/A' : nl2br(e($reservation->description)) !!}
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
                                Reservation Details
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-item is-justify-content-left">
                        <div>
                            <a href="{{ route('reservations.edit', $reservation->id) }}" class="button is-small bg-green has-text-white">
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
            @if ($reservation->isCancelled())
                <div class="box is-shadowless bg-lightpurple has-text-left mb-6">
                    <p class="has-text-grey text-purple is-size-6">
                        <span class="icon">
                            <i class="fas fa-times-circle"></i>
                        </span>
                        <span>
                            This reservation is cancelled
                        </span>
                    </p>
                </div>
            @endif
            @if ($reservation->isConverted())
                <div class="box is-shadowless bg-lightgreen has-text-left mb-6">
                    <p class="has-text-grey text-green is-size-6">
                        <span class="icon">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        <span>
                            This reservation is successfully converted to Delivery Order
                        </span>
                    </p>
                    @can('Cancel Reservation')
                        <form id="formOne" action="{{ route('reservations.cancel', $reservation->id) }}" method="post" novalidate>
                            @csrf
                            <button data-type="Reservation" data-action="cancel" data-description="" class="swal button bg-purple has-text-white mt-5 is-size-7-mobile">
                                <span class="icon">
                                    <i class="fas fa-times-circle"></i>
                                </span>
                                <span>
                                    Cancel Reservation
                                </span>
                            </button>
                        </form>
                    @endcan
                </div>
            @endif
            @if ($reservation->isReserved())
                <div class="box has-background-white-ter has-text-left mb-6">
                    <p class="has-text-grey text-purple is-size-6">
                        <span class="icon">
                            <i class="fas fa-exclamation-circle"></i>
                        </span>
                        <span>
                            Products listed below are reserved but not sold or converted to DO.
                        </span>
                    </p>
                    @can('Convert Reservation')
                        <form id="formOne" action="{{ route('reservations.convert', $reservation->id) }}" method="post" novalidate class="is-inline">
                            @csrf
                            <button data-type="Reservation" data-action="convert" data-description="" class="swal button bg-purple has-text-white mt-5 is-size-7-mobile">
                                <span class="icon">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                                <span>
                                    Convert to DO
                                </span>
                            </button>
                        </form>
                    @endcan
                    @can('Cancel Reservation')
                        <form id="formOne" action="{{ route('reservations.cancel', $reservation->id) }}" method="post" novalidate class="is-inline">
                            @csrf
                            <button data-type="Reservation" data-action="cancel" data-description="" class="swal button btn-purple is-outlined mt-5 is-size-7-mobile">
                                <span class="icon">
                                    <i class="fas fa-times-circle"></i>
                                </span>
                                <span>
                                    Cancel Reservation
                                </span>
                            </button>
                        </form>
                    @endcan
                </div>
            @endif
            @if (!$reservation->isApproved())
                <div class="box has-background-white-ter has-text-left mb-6">
                    <p class="has-text-grey text-purple is-size-6">
                        <span class="icon">
                            <i class="fas fa-exclamation-circle"></i>
                        </span>
                        <span>
                            This Reservation has not been approved.
                        </span>
                    </p>
                    @can('Approve Reservation')
                        <form id="formOne" action="{{ route('reservations.approve', $reservation->id) }}" method="post" novalidate class="is-inline">
                            @csrf
                            <button data-type="Reservation" data-action="approve" data-description="" class="button bg-purple has-text-white mt-5 is-size-7-mobile">
                                <span class="icon">
                                    <i class="fas fa-signature"></i>
                                </span>
                                <span>
                                    Approve Reservation
                                </span>
                            </button>
                        </form>
                    @endcan
                    @can('Cancel Reservation')
                        <form id="formOne" action="{{ route('reservations.cancel', $reservation->id) }}" method="post" novalidate class="is-inline">
                            @csrf
                            <button data-type="Reservation" data-action="cancel" data-description="" class="swal button btn-purple is-outlined mt-5 is-size-7-mobile">
                                <span class="icon">
                                    <i class="fas fa-times-circle"></i>
                                </span>
                                <span>
                                    Cancel Reservation
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
                            <th><abbr> From </abbr></th>
                            <th><abbr> Product </abbr></th>
                            <th><abbr> Quantity </abbr></th>
                            <th><abbr> Unit Price </abbr></th>
                            <th><abbr> Description </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reservation->reservationDetails as $reservationDetail)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized">
                                    {{ $reservationDetail->warehouse->name }}
                                </td>
                                <td class="is-capitalized">
                                    {{ $reservationDetail->product->name }}
                                </td>
                                <td>
                                    {{ number_format($reservationDetail->quantity, 2) }}
                                    {{ $reservationDetail->product->unit_of_measurement }}
                                </td>
                                <td>
                                    {{ $reservation->company->currency }}.
                                    {{ number_format($reservationDetail->unit_price, 2) }}
                                </td>
                                <td>
                                    {!! nl2br(e($reservationDetail->description)) !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
