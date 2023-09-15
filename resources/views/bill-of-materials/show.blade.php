@extends('layouts.app')

@section('title', 'Bill Of Material Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="General Information" />
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-th"
                        :data="$billOfMaterial->product->name"
                        label="Product"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-clipboard-list"
                        :data="$billOfMaterial->name"
                        label="Name"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-info"
                        :data="$billOfMaterial->isActive() ? 'Active' : 'Not Active'"
                        label="Status"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$billOfMaterial->created_at->toFormattedDateString()"
                        label="Issued On"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="money($billOfMaterial->getUnitCost())"
                        label="Unit Cost"
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
                @if (!$billOfMaterial->isApproved())
                    @can('Import BOM')
                        <x-common.dropdown-item>
                            <x-common.button
                                tag="button"
                                mode="button"
                                @click="$dispatch('open-import-modal') "
                                icon="fas fa-upload"
                                label="Import Bill Of Materials"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                    @can('Approve BOM')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('bill-of-materials.approve', $billOfMaterial->id)"
                                action="approve"
                                intention="approve this Bill of material"
                                icon="fas fa-signature"
                                label="Approve"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @endif
                @can('Update BOM')
                    <x-common.dropdown-item>
                        <x-common.button
                            tag="a"
                            href="{{ route('bill-of-materials.edit', $billOfMaterial->id) }}"
                            mode="button"
                            icon="fas fa-pen"
                            label="Edit"
                            class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                        />
                    </x-common.dropdown-item>
                @endcan
            </x-common.dropdown>
        </x-content.header>
        <x-content.footer>
            <x-common.fail-message :message="session('failedMessage')" />
            <x-common.success-message :message="session('successMessage') ?? (session('deleted') ?? session('imported'))" />
            <x-common.fail-message :message="count($errors->all()) ? $errors->all() : null" />
            @if (!$billOfMaterial->isApproved())
                <x-common.fail-message message="This Bill of material has not been approved yet." />
            @endif
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
    @if (!$billOfMaterial->isApproved())
        @can('Import BOM')
            <x-common.import
                title="Import Bill Of Materials"
                action="{{ route('bill-of-materials.import', $billOfMaterial->id) }}"
            />
        @endcan
    @endif
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
