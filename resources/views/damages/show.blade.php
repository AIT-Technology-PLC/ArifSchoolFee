@extends('layouts.app')

@section('title', 'Damage Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="General Information" />
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-bolt"
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
        <x-content.header
            title="Details"
            is-mobile
        >
            <x-common.dropdown name="Actions">
                @if (!$damage->isApproved() && authUser()->can(['Approve Damage', 'Subtract Damage']))
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('damages.approve_and_subtract', $damage->id)"
                                action="approve & subtract"
                                intention="approve & subtract this damage"
                                icon="fas fa-minus-circle"
                                label="Approve & Subtract"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                @elseif (!$damage->isApproved())
                    @can('Approve Damage')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('damages.approve', $damage->id)"
                                action="approve"
                                intention="approve this damage claim"
                                icon="fas fa-signature"
                                label="Approve"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @elseif(!$damage->isSubtracted())
                    @can('Subtract Damage')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('damages.subtract', $damage->id)"
                                action="subtract"
                                intention="subtract the damaged products"
                                icon="fas fa-minus-circle"
                                label="Subtract"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @endif
                <x-common.dropdown-item>
                    <x-common.button
                        tag="a"
                        href="{{ route('damages.edit', $damage->id) }}"
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
