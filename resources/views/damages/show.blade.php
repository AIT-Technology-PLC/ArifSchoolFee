@extends('layouts.app')

@section('title', 'Damage Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="General Information" />
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-file-invoice"
                        :data="$damage->code ?? 'N/A'"
                        label="Damage No"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$damage->issued_on->toFormattedDateString() ?? 'N/A'"
                        label="Issued On"
                    />
                </div>
                <div class="column is-12">
                    <x-common.show-data-section
                        type="long"
                        :data="is_null($damage->description) ? 'N/A' : nl2br(e($damage->description))"
                        label="Details"
                    />
                </div>
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header title="Details">
            @if (!$damage->isApproved())
                @can('Approve Damage')
                    <x-common.transaction-button
                        :route="route('damages.approve', $damage->id)"
                        action="approve"
                        intention="approve this damage claim"
                        icon="fas fa-signature"
                        label="Approve Damage"
                        class="has-text-weight-medium"
                    />
                @endcan
            @elseif(!$damage->isSubtracted())
                @can('Subtract Damage')
                    <x-common.transaction-button
                        :route="route('damages.subtract', $damage->id)"
                        action="subtract"
                        intention="subtract the damaged products"
                        icon="fas fa-minus-circle"
                        label="Subtract from inventory"
                        class="has-text-weight-medium"
                    />
                @endcan
            @endif
            <x-common.button
                tag="a"
                href="{{ route('damages.edit', $damage->id) }}"
                mode="button"
                icon="fas fa-pen"
                label="Edit"
                class="is-small bg-green has-text-white"
            />
        </x-content.header>
        <x-content.footer>
            <x-common.fail-message :message="session('failedMessage')" />
            <x-common.success-message :message="session('successMessage')" />
            @if ($damage->isSubtracted())
                <x-common.success-message message="Products have been subtracted from inventory." />
            @elseif (!$damage->isApproved())
                <x-common.fail-message message="This Damage has not been approved yet." />
            @elseif (!$damage->isSubtracted())
                <x-common.fail-message message="Product(s) listed below are still not subtracted from your inventory." />
            @endif
            <x-common.success-message :message="session('deleted')" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
