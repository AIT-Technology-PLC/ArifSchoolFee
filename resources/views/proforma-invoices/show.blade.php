@extends('layouts.app')

@section('title', 'Proforma Invoice Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="General Information" />
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-file-invoice"
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
                        icon="fas fa-calendar-day"
                        :data="$proformaInvoice->expires_on->toFormattedDateString() ?? 'N/A'"
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
                @if (!userCompany()->isDiscountBeforeVAT())
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-percentage"
                            data="{{ number_format($proformaInvoice->discount * 100, 2) }}%"
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
        <x-content.header title="Details">
            @if ($proformaInvoice->isPending())
                @can('Convert Proforma Invoice')
                    <x-common.transaction-button
                        :route="route('proforma-invoices.convert', $proformaInvoice->id)"
                        action="confirm"
                        intention="confirm this proforma invoice"
                        icon="fas fa-check-circle"
                        label="Confirm"
                    />
                @endcan
                @can('Cancel Proforma Invoice')
                    <x-common.transaction-button
                        :route="route('proforma-invoices.cancel', $proformaInvoice->id)"
                        action="cancel"
                        intention="cancel this proforma invoice"
                        icon="fas fa-times"
                        label="Cancel"
                    />
                @endcan
            @endif
            @if ($proformaInvoice->isConverted() && !$proformaInvoice->isClosed())
                <x-common.transaction-button
                    :route="route('proforma-invoices.close', $proformaInvoice->id)"
                    action="close"
                    intention="close this proforma invoice"
                    icon="fas fa-ban"
                    label="Close"
                />
                @can('Create GDN')
                    <x-common.button
                        tag="a"
                        href="{{ route('proforma-invoices.convert_to_gdn', $proformaInvoice->id) }}"
                        mode="button"
                        icon="fas fa-file-invoice"
                        label="Convert to DO"
                        class="btn-purple is-outlined is-small"
                    />
                @endcan
            @endif
            @if (!$proformaInvoice->isCancelled())
                <x-common.button
                    tag="a"
                    href="{{ route('proforma-invoices.print', $proformaInvoice->id) }}"
                    target="_blank"
                    mode="button"
                    icon="fas fa-print"
                    label="Print"
                    class="btn-purple is-outlined is-small is-hidden-mobile"
                />
            @endif
            <x-common.button
                tag="a"
                href="{{ route('proforma-invoices.edit', $proformaInvoice->id) }}"
                mode="button"
                icon="fas fa-pen"
                label="Edit"
                class="is-small bg-green has-text-white"
            />
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('successMessage')" />
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
