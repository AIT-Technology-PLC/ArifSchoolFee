@extends('layouts.app')

@section('title', 'GRN Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="General Information" />
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-file-import"
                        :data="$grn->code ?? 'N/A'"
                        label="GRN No"
                    />
                </div>
                @if (isFeatureEnabled('Purchase Management') && is_numeric($grn->purchase?->code))
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
        <x-content.header
            title="Details"
            is-mobile
        >
            <x-common.dropdown name="Actions">
                @if (!$grn->isApproved() && authUser()->can(['Approve GRN', 'Add GRN']))
                    <x-common.dropdown-item>
                        <x-common.transaction-button
                            :route="route('grns.approve_and_add', $grn->id)"
                            action="approve & add"
                            intention="approve & add this grn"
                            icon="fas fa-plus-circle"
                            label="Approve & Add"
                            class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                        />
                    </x-common.dropdown-item>
                @elseif (!$grn->isApproved())
                    @can('Approve GRN')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('grns.approve', $grn->id)"
                                action="approve"
                                intention="approve this GRN"
                                icon="fas fa-signature"
                                label="Approve"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @elseif(!$grn->isAdded())
                    @can('Add GRN')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('grns.add', $grn->id)"
                                action="add"
                                intention="add products of this GRN"
                                icon="fas fa-plus-circle"
                                label="Add"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @endif
                <x-common.dropdown-item>
                    <x-common.button
                        tag="a"
                        href="{{ route('grns.edit', $grn->id) }}"
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
