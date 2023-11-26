@extends('layouts.app')

@section('title', 'Reservation Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="General Information" />
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-archive"
                        :data="$reservation->code ?? 'N/A'"
                        label="Reservation No"
                    />
                </div>
                @if ($reservation->bank_name)
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-university"
                            :data="$reservation->bank_name"
                            label="Bank"
                        />
                    </div>
                @endif
                @if ($reservation->reference_number)
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-hashtag"
                            :data="$reservation->reference_number"
                            label="Bank Reference No"
                        />
                    </div>
                @endif
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
                        icon="fas fa-address-card"
                        :data="$reservation->contact->name ?? 'N/A'"
                        label="Contact"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-times"
                        :data="$reservation->expires_on->toFormattedDateString() ?? 'N/A'"
                        label="Expiry Date"
                    />
                </div>
                @if ($reservation->payment_in_credit > 0 && !is_null($reservation->due_date))
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
                @if ($reservation->hasWithholding())
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-hand-holding-usd"
                            data="{{ number_format($reservation->totalWithheldAmount, 2) }}"
                            label="Withholding Tax ({{ userCompany()->currency }})"
                        />
                    </div>
                @endif
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-dollar-sign"
                        :data="number_format($reservation->grandTotalPrice, 2)"
                        label="Grand Total Price ({{ userCompany()->currency }})"
                    />
                </div>
                @if (!userCompany()->isDiscountBeforeTax())
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-percentage"
                            data="{{ $reservation->discount ?? 0 }}%"
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
                @foreach ($reservation->customFieldValues as $field)
                    <div class="column is-6">
                        <x-common.show-data-section
                            :icon="$field->customField->icon"
                            :data="$field->value"
                            :label="$field->customField->label"
                        />
                    </div>
                @endforeach
                <div class="column is-12">
                    <x-common.show-data-section
                        type="long"
                        :data="$reservation->description"
                        label="Details"
                    />
                </div>
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header
            title="Details"
            is-mobile
        >
            <x-common.dropdown name="Actions">
                @if (!$reservation->isCancelled() && !$reservation->isConverted() && $reservation->isReserved() && isFeatureEnabled('Gdn Management', 'Sale Management') && authUser()->can('Convert Reservation'))
                    @if (isFeatureEnabled('Gdn Management'))
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('reservations.convert_to_gdn', $reservation->id)"
                                action="issue"
                                intention="issue delivery order from this reservation"
                                icon="fas fa-check-circle"
                                label="Issue Delivery Order"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endif
                    @if (isFeatureEnabled('Sale Management') && userCompany()->canSaleSubtract())
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('reservations.convert_to_sale', $reservation->id)"
                                action="issue"
                                intention="issue invoice from this reservation"
                                icon="fas fa-check-circle"
                                label="Issue Invoice"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endif
                @elseif(!$reservation->isCancelled() && !$reservation->isReserved() && $reservation->isApproved())
                    @can('Make Reservation')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('reservations.reserve', $reservation->id)"
                                action="reserve"
                                intention="reserve products of this reservation"
                                icon="fas fa-signature"
                                label="Reserve"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @elseif (!$reservation->isCancelled() && !$reservation->isApproved() && authUser()->can(['Approve Reservation', 'Make Reservation']))
                    <x-common.dropdown-item>
                        <x-common.transaction-button
                            :route="route('reservations.approve_and_reserve', $reservation->id)"
                            action="approve & reserve"
                            intention="approve & reserve this reservation"
                            icon="fas fa-signature"
                            label="Approve & Reserve"
                            class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                        />
                    </x-common.dropdown-item>
                @elseif(!$reservation->isCancelled() && !$reservation->isApproved())
                    @can('Approve Reservation')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('reservations.approve', $reservation->id)"
                                action="approve"
                                intention="approve this reservation"
                                icon="fas fa-signature"
                                label="Approve"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @endif

                @if (!$reservation->isCancelled() && $reservation->isApproved() && (!$reservation->isConverted() || !$reservation->reservable->isSubtracted()))
                    @can('Cancel Reservation')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('reservations.cancel', $reservation->id)"
                                action="cancel"
                                intention="cancel this reservation"
                                icon="fas fa-times-circle"
                                label="Cancel"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @endif

                @if ($reservation->isApproved() && !$reservation->isCancelled())
                    <x-common.dropdown-item>
                        <x-common.button
                            tag="a"
                            href="{{ route('reservations.print', $reservation->id) }}"
                            target="_blank"
                            mode="button"
                            icon="fas fa-print"
                            label="Print"
                            class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                        />
                    </x-common.dropdown-item>
                @endif

                <x-common.dropdown-item>
                    <x-common.button
                        tag="a"
                        href="{{ route('reservations.edit', $reservation->id) }}"
                        mode="button"
                        icon="fas fa-pen"
                        label="Edit"
                        class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                    />
                </x-common.dropdown-item>
            </x-common.dropdown>
        </x-content.header>
        <x-content.footer>
            <x-common.fail-message :message="session('failedMessage')" />
            <x-common.success-message :message="session('successMessage')" />
            @if ($reservation->isCancelled())
                <x-common.fail-message message="This reservation is cancelled" />
            @elseif ($reservation->isConverted())
                <x-common.success-message message="This reservation is successfully converted." />
            @elseif ($reservation->isReserved())
                <x-common.fail-message message="Products listed below are reserved but not sold or converted." />
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
