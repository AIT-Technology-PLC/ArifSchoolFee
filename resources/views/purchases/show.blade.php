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
                        icon="fas fa-address-card"
                        :data="$purchase->supplier->company_name ?? 'N/A'"
                        label="Supplier"
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
                        icon="fas fa-dollar-sign"
                        :data="number_format($purchase->subtotalPrice, 2)"
                        label="Subtotal Price ({{ userCompany()->currency }})"
                    />
                </div>
                @if (!$purchase->isImported())
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-dollar-sign"
                            :data="number_format($purchase->grandTotalPrice, 2)"
                            label="Grand Total Price ({{ userCompany()->currency }})"
                        />
                    </div>
                @endif
                @if (!userCompany()->isDiscountBeforeVAT())
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-percentage"
                            data="{{ number_format($purchase->discount * 100, 2) }}%"
                            label="Discount"
                        />
                    </div>
                @endif
                @if (!$purchase->isImported() && !userCompany()->isDiscountBeforeVAT())
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-dollar-sign"
                            :data="number_format($purchase->grandTotalPriceAfterDiscount, 2)"
                            label="Grand Total Price (After Discount)"
                        />
                    </div>
                @endif
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
        <x-content.header title="Details">
            <x-common.dropdown name="Actions"></x-common.dropdown>
                @if (!$purchase->isApproved())
                    @can('Approve Purchase')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('purchases.approve', $purchase->id)"
                                action="approve"
                                intention="approve this purchase"
                                icon="fas fa-signature"
                                label="Approve Purchase"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @elseif(!$purchase->isPurchased())
                    @can('Make Purchase')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('purchases.purchase', $purchase->id)"
                                action="execute"
                                intention="execute this purchase"
                                icon="fas fa-eraser"
                                label="Execute Purchase"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @elseif(!$purchase->isClosed())
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
            @if ($purchase->isPurchased())
                <x-common.success-message message="Products have been purchased accordingly." />
            @elseif (!$purchase->isApproved())
                <x-common.fail-message message="This Purchase has not been approved yet." />
            @elseif (!$purchase->isPurchased())
                <x-common.fail-message message="Product(s) listed below are still not purchased." />
            @endif
            <x-common.success-message :message="session('deleted')" />
            <div> {{ $dataTable->table() }} </div>
        </x-content.footer>
    </x-common.content-wrapper>

    @if (isFeatureEnabled('Grn Management'))
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
