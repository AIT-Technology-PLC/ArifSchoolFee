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
            @if ($reservation->payment_in_credit > 0)
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
                                    {{ $reservation->due_date->toFormattedDateString() ?? 'N/A' }}
                                </div>
                                <div class="is-uppercase is-size-7">
                                    Credit Due Date
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
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
                                {{ number_format($reservation->paymentInCash, 2) }}
                                ({{ number_format($reservation->PaymentPercentInCash, 2) }}%)
                            </div>
                            <div class="is-uppercase is-size-7">
                                In Cash ({{ userCompany()->currency }})
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
                                {{ number_format($reservation->paymentInCredit, 2) }}
                                ({{ number_format($reservation->credit_payable_in_percentage, 2) }}%)
                            </div>
                            <div class="is-uppercase is-size-7">
                                On Credit ({{ userCompany()->currency }})
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
                                {{ number_format($reservation->subtotalPrice, 2) }}
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
                                {{ number_format($reservation->grandTotalPrice, 2) }}
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
                                    {{ number_format($reservation->discount * 100, 2) }}%
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
                                    {{ number_format($reservation->grandTotalPriceAfterDiscount, 2) }}
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
                            <a
                                href="{{ route('reservations.edit', $reservation->id) }}"
                                class="button is-small bg-green has-text-white"
                            >
                                <span class="icon">
                                    <i class="fas fa-pen"></i>
                                </span>
                                <span>
                                    Edit
                                </span>
                            </a>
                            @if ($reservation->reservable && !$reservation->reservable->isSubtracted())
                                @can('Cancel Reservation')
                                    <form
                                        x-data="swal('cancel', 'cancel this reservation')"
                                        class="is-inline"
                                        action="{{ route('reservations.cancel', $reservation->id) }}"
                                        method="post"
                                        novalidate
                                        @submit.prevent="open"
                                    >
                                        @csrf
                                        <button
                                            class="button bg-purple has-text-white is-small is-inline"
                                            x-ref="submitButton"
                                        >
                                            <span class="icon">
                                                <i class="fas fa-times-circle"></i>
                                            </span>
                                            <span>
                                                Cancel Reservation
                                            </span>
                                        </button>
                                    </form>
                                @endcan
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box radius-bottom-0 mb-0 radius-top-0">
            <x-common.fail-message :message="session('failedMessage')" />
            <x-common.success-message :message="session('successMessage')" />
            @if ($reservation->isCancelled())
                <x-common.fail-message message="This reservation is cancelled" />
            @elseif ($reservation->isConverted())
                <x-common.success-message message="This reservation is successfully converted to Delivery Order" />
            @elseif ($reservation->isReserved())
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
                        <form
                            x-data="swal('convert', 'convert this reservation to delivery order')"
                            action="{{ route('reservations.convert', $reservation->id) }}"
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
                                    Convert to DO
                                </span>
                            </button>
                        </form>
                    @endcan
                    @can('Cancel Reservation')
                        <form
                            x-data="swal('cancel', 'cancel this reservation')"
                            action="{{ route('reservations.cancel', $reservation->id) }}"
                            method="post"
                            novalidate
                            class="is-inline"
                            @submit.prevent="open"
                        >
                            @csrf
                            <button
                                class="button btn-purple is-outlined mt-5 is-size-7-mobile"
                                x-ref="submitButton"
                            >
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
            @elseif($reservation->isApproved())
                <div class="box has-background-white-ter has-text-left mb-6">
                    <p class="has-text-grey text-purple is-size-6">
                        <span class="icon">
                            <i class="fas fa-exclamation-circle"></i>
                        </span>
                        <span>
                            This Reservation is approved but products are not yet reserved.
                        </span>
                    </p>
                    @can('Make Reservation')
                        <form
                            x-data="swal('reserve', 'reserve products of this reservation')"
                            action="{{ route('reservations.reserve', $reservation->id) }}"
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
                                    <i class="fas fa-signature"></i>
                                </span>
                                <span>
                                    Make Reservation
                                </span>
                            </button>
                        </form>
                    @endcan
                    @can('Cancel Reservation')
                        <form
                            x-data="swal('cancel', 'cancel this reservation')"
                            action="{{ route('reservations.cancel', $reservation->id) }}"
                            method="post"
                            novalidate
                            class="is-inline"
                            @submit.prevent="open"
                        >
                            @csrf
                            <button
                                class="button btn-purple is-outlined mt-5 is-size-7-mobile"
                                x-ref="submitButton"
                            >
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
            @else
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
                        <form
                            x-data="swal('approve', 'approve this reservation')"
                            action="{{ route('reservations.approve', $reservation->id) }}"
                            method="post"
                            novalidate
                            @submit.prevent="open"
                        >
                            @csrf
                            <button
                                class="button bg-purple has-text-white mt-5 is-size-7-mobile"
                                x-ref="submitButton"
                            >
                                <span class="icon">
                                    <i class="fas fa-signature"></i>
                                </span>
                                <span>
                                    Approve Reservation
                                </span>
                            </button>
                        </form>
                    @endcan
                </div>
            @endif
            <x-common.success-message :message="session('deleted')" />
            {{ $dataTable->table() }}
        </div>
    </section>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
