@extends('layouts.app')

@section('title', 'GRN Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="General Information" />
        <x-content.footer></x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-file-import"
                        :data="$grn->code ?? 'N/A'"
                        label="GRN No"
                    />
                </div>
                @if (isFeatureEnabled('Purchase Management'))
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-hashtag"
                            :data="$grn->purchase->code ?? 'N/A'"
                            label="Purchase No"
                        />
                    </div>
                @endif
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-address-card"
                        :data="$grn->supplier->company_name ?? 'N/A'"
                        label="Supplier"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$grn->issued_on->toFormattedDateString() ?? 'N/A'"
                        label="Issued On"
                    />
                </div>
                <div class="column is-12">
                    <x-common.show-data-section
                        type="long"
                        :data="$grn->description"
                        label="Details"
                    />
                </div>
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header title="Details">
            @if (!$grn->isApproved())
                @can('Approve GRN')
                    <x-common.transaction-button
                        :route="route('grns.approve', $grn->id)"
                        action="approve"
                        intention="approve this GRN"
                        icon="fas fa-signature"
                        label="Approve GRN"
                        class="has-text-weight-medium"
                    />
                @endcan
            @elseif(!$grn->isAdded())
                @can('Add GRN')
                    <x-common.transaction-button
                        :route="route('grns.add', $grn->id)"
                        action="add"
                        intention="add products of this GRN"
                        icon="fas fa-plus-circle"
                        label="Add to Inventory"
                        class="has-text-weight-medium"
                    />
                @endcan
            @endif
            <x-common.button
                tag="a"
                href="{{ route('grns.edit', $grn->id) }}"
                mode="button"
                icon="fas fa-pen"
                label="Edit"
                class="is-small bg-green has-text-white"
            />
        </x-content.header>
        <x-content.footer>
            <x-common.fail-message :message="session('failedMessage')" />
            <x-common.success-message :message="session('successMessage')" />
            @if ($grn->isAdded())
                <x-common.success-message message="Product(s) listed below have been added to your Inventory." />
            @elseif (!$grn->isApproved())
                <x-common.fail-message message="This GRN has not been approved yet." />
            @elseif (!$grn->isAdded())
                <x-common.fail-message message="Product(s) listed below are still not added to your Inventory." />
            @endif
            <x-common.success-message :message="session('deleted')" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
