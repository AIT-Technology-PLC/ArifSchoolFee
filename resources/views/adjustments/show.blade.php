@extends('layouts.app')

@section('title', 'Adjustment Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="General Information" />
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-file-invoice"
                        :data="$adjustment->code"
                        label="Adjustment No"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$adjustment->issued_on->toFormattedDateString()"
                        label="Issued On"
                    />
                </div>
                <div class="column is-12">
                    <x-common.show-data-section
                        type="long"
                        :data="$adjustment->description"
                        label="Details"
                    />
                </div>
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header title="Details">
            @if (!$adjustment->isApproved())
                @can('Approve Adjustment')
                    <x-common.transaction-button
                        :route="route('adjustments.approve', $adjustment->id)"
                        action="approve"
                        intention="approve this adjustment"
                        icon="fas fa-signature"
                        label="Approve Adjustment"
                        class="has-text-weight-medium"
                    />
                @endcan
            @elseif(!$adjustment->isAdjusted())
                @can('Make Adjustment')
                    <x-common.transaction-button
                        :route="route('adjustments.adjust', $adjustment->id)"
                        action="execute"
                        intention="execute this adjustment"
                        icon="fas fa-eraser"
                        label="Execute Adjustment"
                        class="has-text-weight-medium"
                    />
                @endcan
            @endif
            <x-common.button
                tag="a"
                href="{{ route('adjustments.edit', $adjustment->id) }}"
                mode="button"
                icon="fas fa-pen"
                label="Edit"
                class="is-small bg-green has-text-white"
            />
        </x-content.header>
        <x-content.footer>
            <x-common.fail-message :message="session('failedMessage')" />
            <x-common.success-message :message="session('successMessage')" />
            @if ($adjustment->isAdjusted())
                <x-common.success-message message="Products have been adjusted accordingly." />
            @elseif (!$adjustment->isApproved())
                <x-common.fail-message message="This Adjustment has not been approved yet." />
            @elseif (!$adjustment->isAdjusted())
                <x-common.fail-message message="Product(s) listed below are still not adjusted." />
            @endif
            <x-common.success-message :message="session('deleted')" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
