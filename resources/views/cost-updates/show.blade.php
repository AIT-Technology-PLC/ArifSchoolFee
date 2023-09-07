@extends('layouts.app')

@section('title', 'Cost Update Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="General Information" />
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-tag"
                        :data="$costUpdate->code"
                        label="Reference No"
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
                @if (!$costUpdate->isApproved() && !$costUpdate->isRejected() && authUser()->can('Approve Cost Update'))
                    <x-common.dropdown-item>
                        <x-common.transaction-button
                            :route="route('cost-updates.approve', $costUpdate->id)"
                            action="approve"
                            intention="approve this cost update"
                            icon="fas fa-signature"
                            label="Approve"
                            class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                        />
                    </x-common.dropdown-item>
                @endif
                @if (!$costUpdate->isRejected() && !$costUpdate->isApproved())
                    @can('Reject Cost Update')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('cost-updates.reject', $costUpdate->id)"
                                action="reject"
                                intention="reject this cost update"
                                icon="fas fa-eraser"
                                label="Reject"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @endif
                <x-common.dropdown-item>
                    <x-common.button
                        tag="a"
                        href="{{ route('cost-updates.edit', $costUpdate->id) }}"
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
            @if ($costUpdate->isApproved())
                <x-common.success-message message="Cost update have been Approved accordingly." />
            @elseif (!$costUpdate->isApproved() && !$costUpdate->isRejected())
                <x-common.fail-message message="This cost update has not been approved yet." />
            @elseif ($costUpdate->isRejected())
                <x-common.fail-message message="This cost update has rejected." />
            @endif
            <x-common.success-message :message="session('deleted')" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
