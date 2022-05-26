@extends('layouts.app')

@section('title', 'Return Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="General Information" />
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-file-invoice"
                        :data="$return->code ?? 'N/A'"
                        label="Return No"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-user"
                        :data="$return->customer->company_name ?? 'N/A'"
                        label="Customer"
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
        <x-content.header title="Details">
            @if (!$return->isApproved())
                @can('Approve Return')
                    <x-common.transaction-button
                        :route="route('returns.approve', $return->id)"
                        action="approve"
                        intention="approve this return"
                        icon="fas fa-signature"
                        label="Approve Return"
                        class="has-text-weight-medium"
                    />
                @endcan
            @elseif(!$return->isAdded())
                @can('Make Return')
                    <x-common.transaction-button
                        :route="route('returns.add', $return->id)"
                        action="add"
                        intention="add products of this return voucher"
                        icon="fas fa-plus-circle"
                        label="Add to Inventory"
                        class="has-text-weight-medium"
                    />
                @endcan
            @endif
            @if ($return->isApproved())
                <x-common.button
                    tag="a"
                    href="{{ route('returns.print', $return->id) }}"
                    target="_blank"
                    mode="button"
                    icon="fas fa-print"
                    label="Print"
                    class="is-small bg-purple has-text-white is-hidden-mobile"
                />
            @endif
            <x-common.button
                tag="a"
                href="{{ route('returns.edit', $return->id) }}"
                mode="button"
                icon="fas fa-pen"
                label="Edit"
                class="is-small bg-green has-text-white"
            />
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
