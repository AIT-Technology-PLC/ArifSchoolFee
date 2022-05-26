@extends('layouts.app')

@section('title', 'Delivery Order Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="General Information" />
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-file-invoice"
                        :data="$gdn->code ?? 'N/A'"
                        label="DO No"
                    />
                </div>
                @if (isFeatureEnabled('Sale Management'))
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-hashtag"
                            :data="$gdn->sale->code ?? 'N/A'"
                            label="Receipt No"
                        />
                    </div>
                @endif
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-credit-card"
                        :data="$gdn->payment_type ?? 'N/A'"
                        label="Payment Type"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-user"
                        :data="$gdn->customer->company_name ?? 'N/A'"
                        label="Customer"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$gdn->issued_on->toFormattedDateString() ?? 'N/A'"
                        label="Issued On"
                    />
                </div>
                @if ($gdn->payment_in_credit > 0)
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-calendar-day"
                            :data="$gdn->due_date->toFormattedDateString() ?? 'N/A'"
                            label="Credit Due Date"
                        />
                    </div>
                @endif
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-hand-holding-usd"
                        data="{{ number_format($gdn->paymentInCash, 2) }} ({{ number_format($gdn->cashReceivedInPercentage, 2) }}%)"
                        label="In Cash ({{ userCompany()->currency }})"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-money-check"
                        data="{{ number_format($gdn->paymentInCredit, 2) }} ({{ number_format($gdn->credit_payable_in_percentage, 2) }}%)"
                        label="On Credit ({{ userCompany()->currency }})"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-dollar-sign"
                        :data="number_format($gdn->subtotalPrice, 2)"
                        label="SubTotal Price ({{ userCompany()->currency }})"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-dollar-sign"
                        :data="number_format($gdn->grandTotalPrice, 2)"
                        label="Grand Total Price ({{ userCompany()->currency }})"
                    />
                </div>
                @if (!userCompany()->isDiscountBeforeVAT())
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-percentage"
                            data="{{ number_format($gdn->discount * 100, 2) }}%"
                            label="Discount"
                        />
                    </div>
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-dollar-sign"
                            :data="number_format($gdn->grandTotalPriceAfterDiscount, 2)"
                            label="Grand Total Price (After Discount)"
                        />
                    </div>
                @endif
                <div class="column is-12">
                    <x-common.show-data-section
                        type="long"
                        :data="$gdn->description"
                        label="Details"
                    />
                </div>
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header title="Details">
            @if (!$gdn->isApproved())
                @can('Approve GDN')
                    <x-common.transaction-button
                        :route="route('gdns.approve', $gdn->id)"
                        action="approve"
                        intention="approve this delivery order"
                        icon="fas fa-signature"
                        label="Approve"
                        class="has-text-weight-medium"
                    />
                @endcan
            @elseif(!$gdn->isSubtracted())
                @can('Subtract GDN')
                    <x-common.transaction-button
                        :route="route('gdns.subtract', $gdn->id)"
                        action="subtract"
                        intention="subtract products of this delivery order"
                        icon="fas fa-minus-circle"
                        label="Subtract from inventory"
                        class="has-text-weight-medium"
                    />
                @endcan
            @endif
            @if (isFeatureEnabled('Credit Management') && $gdn->isApproved() && !$gdn->credit()->exists() && $gdn->payment_type == 'Credit Payment' && $gdn->customer()->exists())
                @can('Create Credit')
                    <x-common.transaction-button
                        :route="route('gdns.convert_to_credit', $gdn->id)"
                        action="convert"
                        intention="convert this delivery order to credit"
                        icon="fas fa-money-check"
                        label="Convert to Credit"
                    />
                @endcan
            @endif
            @if ($gdn->isSubtracted() && !$gdn->isClosed())
                <x-common.transaction-button
                    :route="route('gdns.close', $gdn->id)"
                    action="close"
                    intention="close this delivery order"
                    icon="fas fa-ban"
                    label="Close"
                />
            @endif
            @if ($gdn->isApproved())
                <x-common.button
                    tag="a"
                    href="{{ route('gdns.print', $gdn->id) }}"
                    target="_blank"
                    mode="button"
                    icon="fas fa-print"
                    label="Print"
                    class="btn-purple is-outlined is-small is-hidden-mobile"
                />
            @endif
            @if ($gdn->isSubtracted() && !$gdn->isClosed())
                @can('Create SIV')
                    <x-common.transaction-button
                        :route="route('gdns.convert_to_siv', $gdn->id)"
                        action="attach"
                        intention="attach SIV to this delivery order"
                        icon="fas fa-file-export"
                        label="Attach SIV"
                    />
                @endcan
            @endif
            <x-common.button
                tag="a"
                href="{{ route('gdns.edit', $gdn->id) }}"
                mode="button"
                icon="fas fa-pen"
                label="Edit"
                class="is-small bg-green has-text-white"
            />
        </x-content.header>
        <x-content.footer>
            <x-common.fail-message :message="session('failedMessage')" />
            <x-common.success-message :message="session('successMessage')" />
            @if ($gdn->isSubtracted())
                <x-common.success-message message="Products have been subtracted from inventory." />
            @elseif (!$gdn->isApproved())
                <x-common.fail-message message="This Delivery Order has not been approved yet." />
            @elseif (!$gdn->isSubtracted())
                <x-common.fail-message message="Product(s) listed below are still not subtracted from your inventory." />
            @endif
            <x-common.success-message :message="session('deleted')" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
    @if (isFeatureEnabled('Siv Management') && $sivs->count())
        <x-common.content-wrapper class="mt-5">
            <x-content.header title="Store Issue Vouchers" />
            <x-content.footer>
                <x-common.bulma-table>
                    <x-slot name="headings">
                        <th> # </th>
                        <th class="has-text-centered"> Siv No </th>
                        <th> Status </th>
                        <th> Issued on </th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($sivs as $siv)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized has-text-centered">
                                    <a
                                        class="text-blue has-text-weight-medium"
                                        href="{{ route('sivs.show', $siv->id) }}"
                                    >
                                        {{ $siv->code }}
                                    </a>
                                </td>
                                <td>
                                    @include('components.datatables.siv-status')
                                </td>
                                <td class="is-capitalized">
                                    {{ $siv->issued_on->toFormattedDateString() }}
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
