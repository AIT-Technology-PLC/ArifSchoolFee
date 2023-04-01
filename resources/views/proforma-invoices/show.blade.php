@extends('layouts.app')

@section('title', 'Proforma Invoice Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="General Information" />
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-receipt"
                        :data="$proformaInvoice->reference ?? 'N/A'"
                        label="Proforma Invoice No"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-user"
                        :data="$proformaInvoice->customer->company_name ?? 'N/A'"
                        label="Customer"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$proformaInvoice->issued_on->toFormattedDateString() ?? 'N/A'"
                        label="Issued On"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-address-card"
                        :data="$proformaInvoice->contact->name ?? 'N/A'"
                        label="Contact"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$proformaInvoice->expires_on?->toFormattedDateString() ?? 'N/A'"
                        label="Expiry Date"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-dollar-sign"
                        :data="number_format($proformaInvoice->subtotalPrice, 2)"
                        label="SubTotal Price ({{ userCompany()->currency }})"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-dollar-sign"
                        :data="number_format($proformaInvoice->grandTotalPrice, 2)"
                        label=" Grand Total Price ({{ userCompany()->currency }})"
                    />
                </div>
                @if (!userCompany()->isDiscountBeforeTax())
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-percentage"
                            data="{{ $proformaInvoice->discount ?? 0 }}%"
                            label="Discount"
                        />
                    </div>
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-dollar-sign"
                            :data="number_format($proformaInvoice->grandTotalPriceAfterDiscount, 2)"
                            label="Grand Total Price (After Discount)"
                        />
                    </div>
                @endif
                <div class="column is-12">
                    <x-common.show-data-section
                        type="long"
                        :data="$proformaInvoice->terms"
                        label="Terms and Conditions"
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
                @if ($proformaInvoice->isPending())
                    @can('Convert Proforma Invoice')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('proforma-invoices.convert', $proformaInvoice->id)"
                                action="confirm"
                                intention="confirm this proforma invoice"
                                icon="fas fa-check-circle"
                                label="Confirm"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                    @can('Cancel Proforma Invoice')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('proforma-invoices.cancel', $proformaInvoice->id)"
                                action="cancel"
                                intention="cancel this proforma invoice"
                                icon="fas fa-times"
                                label="Cancel"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @endif
                @if ($proformaInvoice->isConverted() && !$proformaInvoice->isClosed())
                    <x-common.dropdown-item>
                        <x-common.transaction-button
                            :route="route('proforma-invoices.close', $proformaInvoice->id)"
                            action="close"
                            intention="close this proforma invoice"
                            icon="fas fa-ban"
                            label="Close"
                            class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                        />
                    </x-common.dropdown-item>
                    @can('Create GDN')
                        <x-common.dropdown-item>
                            <x-common.button
                                tag="a"
                                href="{{ route('proforma-invoices.convert_to_gdn', $proformaInvoice->id) }}"
                                mode="button"
                                icon="fas fa-file-invoice"
                                label="Convert to DO"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                    @can('Create Sale')
                        <x-common.dropdown-item>
                            <x-common.button
                                tag="a"
                                href="{{ route('proforma-invoices.convert_to_sale', $proformaInvoice->id) }}"
                                mode="button"
                                icon="fas fa-cash-register"
                                label="Issue Invoice"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                    @foreach (pads() as $pad)
                        @if (in_array('proforma-invoices', $pad->convert_from))
                            @can('convert', $pad->transactions->first())
                                <x-common.dropdown-item>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('transactions.convert_from', [$pad->transactions->first()->id, 'target' => 'proforma-invoices', 'id' => $proformaInvoice->id]) }}"
                                        mode="button"
                                        icon="{{ $pad->icon }}"
                                        label="Issue {{ $pad->name }}"
                                        class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                                    />
                                </x-common.dropdown-item>
                            @endcan
                        @endif
                    @endforeach
                @endif
                @if (!$proformaInvoice->isCancelled())
                    <x-common.dropdown-item>
                        <x-common.button
                            tag="a"
                            href="{{ route('proforma-invoices.print', $proformaInvoice->id) }}"
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
                        href="{{ route('proforma-invoices.edit', $proformaInvoice->id) }}"
                        mode="button"
                        icon="fas fa-pen"
                        label="Edit"
                        class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                    />
                </x-common.dropdown-item>
            </x-common.dropdown>
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('successMessage')" />
            <x-common.fail-message :message="session('failedMessage')" />
            @if ($proformaInvoice->isCancelled())
                <x-common.fail-message message="This Proforma Invoice has been cancelled." />
            @elseif ($proformaInvoice->isConverted())
                <x-common.success-message message="This Proforma Invoice has been confirmed." />
            @elseif ($proformaInvoice->isPending())
                <x-common.fail-message message="This Proforma Invoice is still pending." />
            @endif
            <x-common.success-message :message="session('deleted')" />
            <div>
                {{ $dataTable->table() }}
            </div>
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
