@extends('layouts.app')

@section('title', 'Reservation Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="General Information" />
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-file-invoice"
                        :data="$reservation->code ?? 'N/A'"
                        label="Reservation No"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-credit-card"
                        :data="$reservation->payment_type ?? 'N/A'"
                        label="Payment Type"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-user"
                        :data="$reservation->customer->company_name ?? 'N/A'"
                        label="Customer"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$reservation->issued_on->toFormattedDateString() ?? 'N/A'"
                        label="Issued On"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-times"
                        :data="$reservation->expires_on->toFormattedDateString() ?? 'N/A'"
                        label="Expiry Date"
                    />
                </div>
                @if ($reservation->payment_in_credit > 0)
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-calendar-day"
                            :data="$reservation->due_date->toFormattedDateString() ?? 'N/A'"
                            label="Credit Due Date"
                        />
                    </div>
                @endif
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-hand-holding-usd"
                        data="{{ number_format($reservation->paymentInCash, 2) }} ({{ number_format($reservation->cashReceivedInPercentage, 2) }}%)"
                        label="In Cash ({{ userCompany()->currency }})"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-money-check"
                        data="{{ number_format($reservation->paymentInCredit, 2) }} ({{ number_format($reservation->creditPayableInPercentage, 2) }}%)"
                        label="On Credit ({{ userCompany()->currency }})"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-dollar-sign"
                        :data="number_format($reservation->subtotalPrice, 2)"
                        label="SubTotal Price ({{ userCompany()->currency }})"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-dollar-sign"
                        :data="number_format($reservation->grandTotalPrice, 2)"
                        label="Grand Total Price ({{ userCompany()->currency }})"
                    />
                </div>
                @if (!userCompany()->isDiscountBeforeVAT())
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-percentage"
                            data="{{ number_format($reservation->discount * 100, 2) }}%"
                            label="Discount"
                        />
                    </div>
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-dollar-sign"
                            :data="number_format($reservation->grandTotalPriceAfterDiscount, 2)"
                            label="Grand Total Price (After Discount)"
                        />
                    </div>
                @endif
                <div class="column is-12">
                    <x-common.show-data-section
                        type="long"
                        :data="is_null($reservation->description) ? 'N/A' : nl2br(e($reservation->description))"
                        label="Details"
                    />
                </div>
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header title="Details">
            @if ($reservation->reservable && !$reservation->reservable->isSubtracted())
                @can('Cancel Reservation')
                    <x-common.transaction-button
                        :route="route('reservations.cancel', $reservation->id)"
                        action="cancel"
                        intention="cancel this reservation"
                        icon="fas fa-times-circle"
                        label="Cancel Reservation"
                        class="has-text-weight-medium"
                    />
                @endcan
            @elseif($reservation->isReserved())
                @can('Convert Reservation')
                    <x-common.transaction-button
                        :route="route('reservations.convert', $reservation->id)"
                        action="convert"
                        intention="convert this reservation to delivery order"
                        icon="fas fa-check-circle"
                        label="Convert to DO"
                        class="has-text-weight-medium"
                    />
                @endcan
                @can('Cancel Reservation')
                    <x-common.transaction-button
                        :route="route('reservations.cancel', $reservation->id)"
                        action="cancel"
                        intention="cancel this reservation"
                        icon="fas fa-times-circle"
                        label="Cancel Reservation"
                        class="has-text-weight-medium"
                    />
                @endcan
            @elseif($reservation->isApproved())
                @can('Make Reservation')
                    <x-common.transaction-button
                        :route="route('reservations.reserve', $reservation->id)"
                        action="reserve"
                        intention="reserve products of this reservation"
                        icon="fas fa-signature"
                        label="Make Reservation"
                        class="has-text-weight-medium"
                    />
                @endcan
                @can('Cancel Reservation')
                    <x-common.transaction-button
                        :route="route('reservations.cancel', $reservation->id)"
                        action="cancel"
                        intention="cancel this reservation"
                        icon="fas fa-times-circle"
                        label="Cancel"
                        class="has-text-weight-medium"
                    />
                @endcan
            @else
                @can('Approve Reservation')
                    <x-common.transaction-button
                        :route="route('reservations.approve', $reservation->id)"
                        action="approve"
                        intention="approve this reservation"
                        icon="fas fa-signature"
                        label="Approve Reservation"
                        class="has-text-weight-medium"
                    />
                @endcan
            @endif
            @if ($reservation->isApproved() && !$reservation->isCancelled())
                <x-common.button
                    tag="a"
                    href="{{ route('reservations.print', $reservation->id) }}"
                    target="_blank"
                    mode="button"
                    icon="fas fa-print"
                    label="Print"
                    class="btn-purple is-outlined is-small is-hidden-mobile"
                />
            @endif
            <x-common.button
                tag="a"
                href="{{ route('reservations.edit', $reservation->id) }}"
                mode="button"
                icon="fas fa-pen"
                label="Edit"
                class="is-small bg-green has-text-white"
            />
        </x-content.header>
        <x-content.footer>
            <x-common.fail-message :message="session('failedMessage')" />
            <x-common.success-message :message="session('successMessage')" />
            @if ($reservation->isCancelled())
                <x-common.fail-message message="This reservation is cancelled" />
            @elseif ($reservation->isConverted())
                <x-common.success-message message="This reservation is successfully converted to Delivery Order" />
            @elseif ($reservation->isReserved())
                <x-common.fail-message message="Products listed below are reserved but not sold or converted to DO." />
            @elseif($reservation->isApproved())
                <x-common.fail-message message="This Reservation is approved but products are not yet reserved." />
            @else
                <x-common.fail-message message="This Reservation has not been approved yet." />
            @endif
            <x-common.success-message :message="session('deleted')" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
