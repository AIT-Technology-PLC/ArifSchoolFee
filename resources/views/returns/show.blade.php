@extends('layouts.app')

@section('title', 'Return Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="General Information" />
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-arrow-alt-circle-left"
                        :data="$return->code ?? 'N/A'"
                        label="Return No"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-user"
                        :data="$return->gdn->customer->company_name ?? ($return->customer->company_name ?? 'N/A')"
                        label="Customer"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-file-invoice"
                        :data="$return->gdn->code ?? 'N/A'"
                        label="Delivery Order No"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$return->issued_on->toFormattedDateString() ?? 'N/A'"
                        label="Issued On"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-dollar-sign"
                        :data="number_format($return->subtotalPrice, 2)"
                        label="SubTotal Price ({{ userCompany()->currency }})"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-dollar-sign"
                        :data="number_format($return->grandTotalPrice, 2)"
                        label="Grand Total Price ({{ userCompany()->currency }})"
                    />
                </div>
                <div class="column is-12">
                    <x-common.show-data-section
                        type="long"
                        :data="is_null($return->description) ? 'N/A' : nl2br(e($return->description))"
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
                @if (!$return->isApproved() && authUser()->can(['Approve Return', 'Make Return']))
                    @can(['Approve Return', 'Make Return'])
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('returns.approve_and_add', $return->id)"
                                action="approve & add"
                                intention="approve & add this return"
                                icon="fas fa-plus-circle"
                                label="Approve & Add"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @elseif (!$return->isApproved())
                    @can('Approve Return')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('returns.approve', $return->id)"
                                action="approve"
                                intention="approve this return"
                                icon="fas fa-signature"
                                label="Approve"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @elseif(!$return->isAdded())
                    @can('Make Return')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('returns.add', $return->id)"
                                action="add"
                                intention="add products of this return voucher"
                                icon="fas fa-plus-circle"
                                label="Add"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @endif
                @if ($return->isApproved())
                    <x-common.dropdown-item>
                        <x-common.button
                            tag="a"
                            href="{{ route('returns.print', $return->id) }}"
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
                        href="{{ route('returns.edit', $return->id) }}"
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
            @if ($return->isAdded())
                <x-common.success-message message="Products have been added to the inventory." />
            @elseif (!$return->isApproved())
                <x-common.fail-message message="This Return has not been approved yet." />
            @elseif (!$return->isAdded())
                <x-common.fail-message message="Product(s) listed below are still not added to the inventory." />
            @endif
            <x-common.success-message :message="session('deleted')" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
