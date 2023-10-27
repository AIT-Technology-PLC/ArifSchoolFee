@extends('layouts.app')

@section('title', 'Purchase Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="General Information" />
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-hashtag"
                        :data="$purchase->code"
                        label="Purchase No"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$purchase->purchased_on->toFormattedDateString()"
                        label="Issued On"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-shopping-bag"
                        :data="$purchase->type"
                        label="Purchase Type"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-credit-card"
                        :data="$purchase->payment_type"
                        label="Payment Type"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-user"
                        :data="$purchase->supplier->company_name ?? 'N/A'"
                        label="Supplier"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-address-card"
                        :data="$purchase->contact->name ?? 'N/A'"
                        label="Contact"
                    />
                </div>
                @if (!$purchase->isImported())
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fa fa-file-invoice-dollar"
                            :data="$purchase->taxModel->type"
                            label="Tax Type"
                        />
                    </div>
                @endif
                @if ($purchase->isImported())
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-money-bill"
                            :data="$purchase->currency"
                            label="Currency"
                        />
                    </div>
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-arrow-trend-up"
                            :data="number_format($purchase->exchange_rate, 4)"
                            label="Exchange Rate ({{ userCompany()->currency }})"
                        />
                    </div>
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-dollar-sign"
                            :data="number_format($purchase->freight_cost, 2)"
                            label="Freight Cost ({{ userCompany()->currency }})"
                        />
                    </div>
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-dollar-sign"
                            :data="number_format($purchase->freight_insurance_cost, 2)"
                            label="Freight Insurance Cost ({{ userCompany()->currency }})"
                        />
                    </div>
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-dollar-sign"
                            :data="number_format($purchase->other_costs_before_tax, 2)"
                            label="Other Cost Before Tax ({{ userCompany()->currency }})"
                        />
                    </div>
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-dollar-sign"
                            :data="number_format($purchase->other_costs_after_tax, 2)"
                            label="Other Cost After Tax ({{ userCompany()->currency }})"
                        />
                    </div>
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-balance-scale"
                            :data="quantity($purchase->purchaseDetails->sum('amount'), $purchase->freight_unit)"
                            label="Freight Volume"
                        />
                    </div>
                @endif
                @if (!$purchase->isImported())
                    @if ($purchase->payment_in_debt > 0)
                        <div class="column is-6">
                            <x-common.show-data-section
                                icon="fas fa-hand-holding-usd"
                                data="{{ number_format($purchase->paymentInCash, 2) }} ({{ number_format($purchase->cashPaidInPercentage, 2) }}%)"
                                label="In Cash ({{ userCompany()->currency }})"
                            />
                        </div>
                        <div class="column is-6">
                            <x-common.show-data-section
                                icon="fas fa-money-check"
                                data="{{ number_format($purchase->paymentInDebt, 2) }} ({{ number_format($purchase->debtPayableInPercentage, 2) }}%)"
                                label="On Credit ({{ userCompany()->currency }})"
                            />
                        </div>
                    @endif
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-dollar-sign"
                            :data="number_format($purchase->subtotalPrice, 2)"
                            label="Subtotal Price ({{ userCompany()->currency }})"
                        />
                    </div>
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fa fa-dollar-sign"
                            :data="$purchase->tax"
                            :label="$purchase->taxModel->type"
                        />
                    </div>
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-dollar-sign"
                            :data="number_format($purchase->grandTotalPrice, 2)"
                            label="Grand Total Price ({{ userCompany()->currency }})"
                        />
                    </div>
                @endif
                @if ($purchase->isImported())
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fa fa-dollar-sign"
                            :data="number_format($purchase->purchaseDetails->sum('withHoldingTaxAmount'), 2)"
                            label="Total Withholding Tax ({{ userCompany()->currency }})"
                        />
                    </div>
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fa fa-dollar-sign"
                            :data="number_format($purchase->purchaseDetails->sum('totalPayableTax'), 2)"
                            label="Total Payable Tax ({{ userCompany()->currency }})"
                        />
                    </div>
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fa fa-dollar-sign"
                            :data="number_format($purchase->purchaseDetails->sum('totalCostAfterTax'), 2)"
                            label="Grand Total Cost After Tax ({{ userCompany()->currency }})"
                        />
                    </div>
                @endif
                @foreach ($purchase->customFieldValues as $field)
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
                        :data="$purchase->description"
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
                @if (!$purchase->isApproved() && !$purchase->isRejected() && authUser()->can(['Approve Purchase', 'Make Purchase']))
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('purchases.approve_and_purchase', $purchase->id)"
                                action="approve & execute"
                                intention="approve & execute this purchase"
                                icon="fas fa-check"
                                label="Approve & Execute"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @can('Reject Purchase')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('purchases.reject', $purchase->id)"
                                action="reject"
                                intention="reject this purchase"
                                icon="fas fa-times-circle"
                                label="Reject"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @elseif (!$purchase->isApproved() && !$purchase->isRejected())
                    @can('Approve Purchase')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('purchases.approve', $purchase->id)"
                                action="approve"
                                intention="approve this purchase"
                                icon="fas fa-signature"
                                label="Approve"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                    @can('Reject Purchase')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('purchases.reject', $purchase->id)"
                                action="reject"
                                intention="reject this purchase"
                                icon="fas fa-times-circle"
                                label="Reject"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @elseif($purchase->isApproved() && !$purchase->isPurchased() && !$purchase->isCancelled())
                    @can('Make Purchase')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('purchases.purchase', $purchase->id)"
                                action="execute"
                                intention="execute this purchase"
                                icon="fas fa-check"
                                label="Execute"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                    @can('Cancel Purchase')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('purchases.cancel', $purchase->id)"
                                action="cancel"
                                intention="cancel this purchase"
                                icon="fas fa-times-circle"
                                label="Cancel"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @elseif($purchase->isPurchased() && !$purchase->isClosed())
                    @if (isFeatureEnabled('Grn Management'))
                        @can('Create GRN')
                            <x-common.dropdown-item>
                                <x-common.button
                                    tag="a"
                                    icon="fas fa-plus"
                                    label="Issue GRN"
                                    mode="button"
                                    :href="route('purchases.convert_to_grn', $purchase->id)"
                                    class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                                />
                            </x-common.dropdown-item>
                        @endcan
                    @endif
                    @can('Close Purchase')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('purchases.close', $purchase->id)"
                                action="close"
                                intention="close this purchase"
                                icon="fas fa-ban"
                                label="Close"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @endif
                @if (isFeatureEnabled('Debt Management') && $purchase->isApproved() && !$purchase->isCancelled() && !$purchase->debt()->exists() && $purchase->payment_type == 'Credit Payment' && $purchase->supplier()->exists())
                    @can('Convert To Debt')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('purchases.convert_to_debt', $purchase->id)"
                                action="convert"
                                intention="convert this purchase to debt"
                                icon="fas fa-money-check-dollar"
                                label="Convert to Debt"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @endif
                <x-common.dropdown-item>
                    <x-common.button
                        tag="a"
                        href="{{ route('purchases.edit', $purchase->id) }}"
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
            @if ($purchase->isRejected())
                <x-common.fail-message message="This Purchase has rejected." />
            @elseif ($purchase->isPurchased())
                <x-common.success-message message="Products have been purchased accordingly." />
            @elseif ($purchase->isCancelled())
                <x-common.fail-message message="This Purchase is cancelled." />
            @elseif ($purchase->isApproved() && !$purchase->isCancelled())
                <x-common.success-message message="This Purchase has approved but not purchased." />
            @elseif (!$purchase->isApproved() && !$purchase->isRejected())
                <x-common.fail-message message="This Purchase has not been approved yet." />
            @elseif (!$purchase->isPurchased())
                <x-common.fail-message message="Product(s) listed below are still not purchased." />
            @endif
            <x-common.success-message :message="session('deleted')" />
            <div> {{ $dataTable->table() }} </div>
        </x-content.footer>
    </x-common.content-wrapper>

    @if (isFeatureEnabled('Grn Management') && $purchase->grns->isNotEmpty())
        <x-common.content-wrapper class="mt-5">
            <x-content.header title="Goods Received Notes" />
            <x-content.footer>
                <x-common.bulma-table>
                    <x-slot name="headings">
                        <th> # </th>
                        <th class="has-text-centered"> GRN No </th>
                        <th> Status </th>
                        <th> Issued on </th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($purchase->grns as $grn)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized has-text-centered">
                                    <a
                                        class="text-blue has-text-weight-medium"
                                        href="{{ route('grns.show', $grn->id) }}"
                                    >
                                        {{ $grn->code }}
                                    </a>
                                </td>
                                <td>
                                    @include('components.datatables.grn-status')
                                </td>
                                <td class="is-capitalized">
                                    {{ $grn->issued_on->toFormattedDateString() }}
                                </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.bulma-table>
            </x-content.footer>
        </x-common.content-wrapper>
    @endif
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
